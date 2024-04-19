<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceCustomer;
use App\Models\Customer;
use App\Models\HistoryBalanceCustomer;
use Illuminate\Http\Request;

class BalanceCustomerController extends Controller
{
    public function index($idCustomer)
    {
        $balance = BalanceCustomer::with('customer', 'historyBalance')->where('customer_id', $idCustomer)->first();
        if (!$balance) {
            // If $balance is null, create a new record
            BalanceCustomer::create(['nominal' => 0, 'customer_id' => $idCustomer]);
        }
        return view('pages.customer.balance.index', compact('balance'));
    }

    public function deposit(Request $request, $idBalance)
    {
        try {
            $balance = BalanceCustomer::where('id', $idBalance)->first();
            $nominalAwal = $balance->nominal;
            $nominalAkhir = $balance->nominal + $request->nominal;
            HistoryBalanceCustomer::create([
                'number' => 'HBC-' . date('YmdHis'),
                'status' => 1,
                'note' => $request->note,
                'nominal' => $request->nominal,
                'start_balance' => $nominalAwal,
                'end_balance' => $nominalAkhir,
                'balance_id' => $balance->id,
            ]);
            BalanceCustomer::where('id', $balance->id)->update(['nominal' => $nominalAkhir]);


            FlashData::success_alert('Berhasil melakukan deposit');
            return redirect()->route('customer.balance_customer', $balance->customer_id);
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function pengeluaran(Request $request, $idBalance)
    {
        try {
            $balance = BalanceCustomer::where('id', $idBalance)->first();
            $nominalAwal = $balance->nominal;
            $nominalAkhir = $balance->nominal - $request->nominal;
            HistoryBalanceCustomer::create([
                'number' => 'HBC-' . date('YmdHis'),
                'status' => 2,
                'note' => $request->note,
                'nominal' => $request->nominal,
                'start_balance' => $nominalAwal,
                'end_balance' => $nominalAkhir,
                'balance_id' => $balance->id,
            ]);
            BalanceCustomer::where('id', $balance->id)->update(['nominal' => $nominalAkhir]);


            FlashData::success_alert('Berhasil melakukan pengeluaran balance');
            return redirect()->route('customer.balance_customer', $balance->customer_id);
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
