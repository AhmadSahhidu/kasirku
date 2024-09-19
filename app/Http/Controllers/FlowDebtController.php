<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\FlowDebt;
use App\Models\FlowDetbPaymentHistory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class FlowDebtController extends Controller
{
    public function index()
    {
        $flowDebt = FlowDebt::with('supplier')->get();
        $paidDebt = 0;
        $remainingDebt = 0;
        $total = 0;

        foreach ($flowDebt as $items) {
            $paidDebt += $items->paid_debt;
            $remainingDebt += $items->remaining_debt;
            $total += $items->amount;
        }


        return view('pages.debt.index', compact('flowDebt', 'paidDebt', 'remainingDebt', 'total'));
    }

    public function create()
    {
        return view('pages.debt.create');
    }

    public function searchSupplier(Request $request)
    {
        $supplier = Supplier::where('name', 'LIKE', '%' . $request->supplier_name . '%')->get();
        return response()->json(['data' => $supplier]);
    }

    public function prosesHutang(Request $request)
    {
        try {
            $flow = null;
            if (!$request->input('supplier_id')) {
                $supplier = Supplier::create([
                    'id' => generateUuid(),
                    'name' => $request->supplier_name,
                    'phone' => $request->supplier_phone,
                    'address' => $request->supplier_address,
                    'sales_name' => '',
                    'sales_phone' => '',
                    'store_id' => ''
                ]);

                $flow = FlowDebt::create([
                    'id' => generateUuid(),
                    'number' => 'FDT' . date('YmdHis'),
                    'supplier_id' => $supplier->id,
                    'no_invoice' => $request->no_invoice,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'due_date' => $request->due_date,
                    'tanggal' => $request->tanggal,
                    'remaining_debt' => $request->payment_method === '1' ? 0 : $request->amount,
                    'status' => $request->payment_method === '1' ? 'Lunas' : 'Belum Lunas',

                ]);
            } else {
                $flow = FlowDebt::create([
                    'id' => generateUuid(),
                    'number' => 'FDT' . date('YmdHis'),
                    'supplier_id' => $request->supplier_id,
                    'no_invoice' => $request->no_invoice,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'due_date' => $request->due_date,
                    'tanggal' => $request->tanggal,
                    'remaining_debt' => $request->payment_method === '1' ? 0 : $request->amount,
                    'paid_debt' => $request->payment_method === '1' ?  $request->amount : 0,
                    'status' => $request->payment_method === '1' ? 'Lunas' : 'Belum Lunas',

                ]);
            }

            if ($flow->payment_method === '1') {
                FlowDetbPaymentHistory::create([
                    'flow_debt_id' => $flow->id,
                    'tanggal' => date('Y-m-d'),
                    'payment_method' => $flow->payment_method,
                    'amount' => $request->amount,
                    'paid_debt' => $request->amount,

                ]);
            }



            FlashData::success_alert('Berhasil menambahkan data hutang');
            return redirect()->route('debt.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function show($debtId)
    {
        $debt = FlowDebt::with('supplier')->where('id', $debtId)->first();
        $payment = FlowDetbPaymentHistory::where('flow_debt_id', $debt->id)->get();

        return view('pages.debt.detail', compact('debt', 'payment'));
    }

    public function prosesPaymentDebt(Request $request, $flowdebtId)
    {
        try {
            $flowdebt = FlowDebt::where('id', $flowdebtId)->first();

            $pembayaran = $request->amount_paid;
            $paidDebt = $flowdebt->paid_debt;
            $remainingDebt = $flowdebt->remaining_debt;
            $condition = $flowdebt->remaining_debt - $pembayaran;

            if ($pembayaran > $remainingDebt) {
                FlashData::danger_alert('Nominal pembayaran melebihi jumlah yang dibayar');
                return redirect()->back();
            }

            FlowDetbPaymentHistory::create([
                'flow_debt_id' => $flowdebt->id,
                'tanggal' => date('Y-m-d'),
                'payment_method' => $request->payment_method,
                'amount' => $pembayaran,
                'paid_debt' => $flowdebt->paid_debt + $pembayaran,
                'remaining_debt' => $flowdebt->remaining_debt - $pembayaran,
            ]);

            FlowDebt::where('id', $flowdebt->id)->update([
                'paid_debt' => $flowdebt->paid_debt + $pembayaran,
                'remaining_debt' => $condition,
                'status' => $condition === 0 ? 'Lunas' : 'Belum Lunas'
            ]);

            FlashData::success_alert('Berhasil melakukan pembayaran hutang');
            return redirect()->back();
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
