<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceCustomer;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\DebtPaymentHistory;
use App\Models\HistoryBalanceCustomer;
use App\Models\SaleDebtPayment;
use App\Models\Sales;
use App\Models\Store;
use Illuminate\Http\Request;

class DebtPaymentController extends Controller
{
    public function index($paymentId)
    {
        $salesDebt = SaleDebtPayment::with('sale')->where('id', $paymentId)->first();
        return view('pages.debt-payment.index', compact('salesDebt'));
    }

    public function paymentDebt(Request $request, $saledebtId)
    {
        try {
            $saleDebt = SaleDebtPayment::with('sale')->where('id', $saledebtId)->first();
            $paymentMethod = $request->payment_method;

            if ($paymentMethod === '3') {
                $balanceCustomer = BalanceCustomer::where('customer_id', $saleDebt->sale->customer_id)->first();
                if ($balanceCustomer->nominal < $saleDebt->remaining) {
                    FlashData::danger_alert('Saldo customer tidak mencukupi');
                    return redirect()->back();
                }

                $saldoCustomer = $balanceCustomer->nominal;
                $saldoAkhir = $balanceCustomer->nominal - $saleDebt->remaining;
                $paid = $saleDebt->remaining;

                //proses simpan history pembayaran tagihan
                DebtPaymentHistory::create([
                    'number' => 'DBH' . date('YmdHis'),
                    'sale_debt_id' => $saleDebt->id,
                    'payment_method' => $paymentMethod,
                    'paid' => $paid,
                    'user_id' => auth()->user()->id
                ]);

                //proses pengurangan balance customer
                BalanceCustomer::where('customer_id', $balanceCustomer->customer_id)->update(['nominal' => $saldoAkhir]);

                //proses pembuatan history balance customer
                HistoryBalanceCustomer::create([
                    'number' => 'HBC-' . date('YmdHis'),
                    'status' => 2,
                    'note' => 'Pembayaran Tagian Jatuh Tempo Transaksi ' . $saleDebt->sale->number,
                    'nominal' => $paid,
                    'start_balance' => $saldoCustomer,
                    'end_balance' => $saldoAkhir,
                    'balance_id' => $balanceCustomer->id,
                ]);

                //proses update status tagihan
                SaleDebtPayment::where('id', $saleDebt->id)->update([
                    'paid' => $paid,
                    'remaining' => $saleDebt->remaining === $paid ? 0 : $saleDebt->remaining - $paid,
                    'status' => $saleDebt->remaining === $paid ? 2 : 1
                ]);

                //proses penambahan cash masuk
                CashFlow::create([
                    'number' => 'CSF-' . date('YmdHis'),
                    'type_cash' => 1,
                    'type_cash_out' => null,
                    'amount' => $paid,
                    'note' => 'Pembayaran tagihan pada transaksi ' . $saleDebt->sale->number,
                    'store_id' => $saleDebt->sale->store_id
                ]);

                FlashData::success_alert('Berhasil membayara tagihan pada transaksi ' . $saleDebt->sale->number);
                return redirect()->route('report.report_debt');
            }

            //proses simpan history pembayaran tagihan
            DebtPaymentHistory::create([
                'number' => 'DBH' . date('YmdHis'),
                'sale_debt_id' => $saleDebt->id,
                'payment_method' => $paymentMethod,
                'paid' => $request->paid,
                'user_id' => auth()->user()->id
            ]);


            //proses update status tagihan
            SaleDebtPayment::where('id', $saleDebt->id)->update([
                'paid' => $request->paid,
                'remaining' => $saleDebt->remaining === $request->paid ? 0 : $saleDebt->remaining - $request->paid,
                'status' => ($request->paid - $saleDebt->remaining) >= 0 ? 2 : 1
            ]);

            //proses penambahan cash masuk
            CashFlow::create([
                'number' => 'CSF-' . date('YmdHis'),
                'type_cash' => 1,
                'type_cash_out' => null,
                'amount' => $request->paid,
                'note' => 'Pembayaran tagihan pada transaksi ' . $saleDebt->sale->number,
                'store_id' => $saleDebt->sale->store_id
            ]);
            FlashData::success_alert('Berhasil membayara tagihan pada transaksi ' . $saleDebt->sale->number);
            return redirect()->route('report.report_debt');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }


    public function autoBalanceCustomer(Request $request)
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $customer = Customer::with('store')->get();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $customer = Customer::with('store')->where('store_id', $stores->id)->get();
        }

        $balance = null;
        $salesDebt = null;
        $dataAutoBalance = array();
        if (request('customer_id')) {
            $balance = BalanceCustomer::with('customer', 'historyBalance')->where('customer_id', request('customer_id'))->first();
            $dataDebt = SaleDebtPayment::query();
            $dataDebt->join('sales', 'sales.id', '=', 'sale_debt_payments.sale_id');
            $dataDebt->where('sales.customer_id', request('customer_id'));
            $dataDebt->where('status', 1);
            $salesDebt = $dataDebt->get();


            $dataAutoBalance = array();
            $totalDebt = 0;

            foreach ($salesDebt as $item) {
                if ($totalDebt + $item->remaining < $balance->nominal) {
                    $dataAutoBalance[] = $item;
                }
            }

            // Debug: periksa apakah data berhasil dimasukkan ke dalam array

        }

        return view('pages.debt-payment.auto-balance.index', compact('customer', 'balance', 'salesDebt', 'dataAutoBalance'));
    }

    public function prosesAutoBalanceCustomer(Request $request)
    {
        try {
            $balance = BalanceCustomer::with('customer', 'historyBalance')->where('customer_id', $request->idCustomer)->first();
            $dataDebt = SaleDebtPayment::query();
            $dataDebt->join('sales', 'sales.id', '=', 'sale_debt_payments.sale_id');
            $dataDebt->select('sale_debt_payments.*');
            $dataDebt->where('sales.customer_id', $request->idCustomer);
            $dataDebt->where('sale_debt_payments.status', 1);
            $salesDebt = $dataDebt->get();


            $dataAutoBalance = array();
            $totalDebt = 0;
            $transaksi = [];

            foreach ($salesDebt as $item) {
                if ($totalDebt + $item->remaining < $balance->nominal) {
                    $dataAutoBalance[] = $item;
                    $totalDebt += $item->remaining;
                    $transaksi[] = $item->sale->number;
                }
            }

            // proses pembuatan history balance customer
            HistoryBalanceCustomer::create([
                'number' => 'HBC-' . date('YmdHis'),
                'status' => 2,
                'note' => 'Pembayaran Tagian Jatuh Tempo Transaksi ' . implode(',', $transaksi),
                'nominal' => $totalDebt,
                'start_balance' => $balance->nominal,
                'end_balance' => $balance->nominal - $totalDebt,
                'balance_id' => $balance->id,
            ]);

            BalanceCustomer::where('customer_id', $balance->customer_id)->update(['nominal' => $balance->nominal - $totalDebt]);

            foreach ($dataAutoBalance as $list) {
                SaleDebtPayment::where('id', $list->id)->update([
                    'paid' => $list->remaining,
                    'remaining' => 0,
                    'status' => 2
                ]);

                //proses simpan history pembayaran tagihan
                DebtPaymentHistory::create([
                    'number' => 'DBH' . date('YmdHis'),
                    'sale_debt_id' => $list->id,
                    'payment_method' => 3,
                    'paid' => $list->remaining,
                    'user_id' => auth()->user()->id
                ]);
            }


            CashFlow::create([
                'number' => 'CSF-' . date('YmdHis'),
                'type_cash' => 1,
                'type_cash_out' => null,
                'amount' => $totalDebt,
                'note' => 'Pembayaran tagihan pada transaksi ' . implode(',', $transaksi),
                'store_id' => $balance->customer->store_id
            ]);
            return response()->json(['message' => 'Berhasil melakukan auto balance customer', 'status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error']);
        }
    }
}
