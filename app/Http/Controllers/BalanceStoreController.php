<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceStoreHistory;
use App\Models\BalanceStores;
use App\Models\Store;
use Illuminate\Http\Request;

class BalanceStoreController extends Controller
{
    public function index(Request $request)
    {
        $history = [];
        $balance = null;
        $store = [];

        if ($request->store === null || $request->store === '') {
            $store = Store::all();
        } else {
            $databalance = BalanceStores::where('store_id', $request->store)->count();
            if ($databalance === 0) {
                BalanceStores::create([
                    'amount_in_hand' => 0,
                    'amount_in_cashier' => 0,
                    'amount_customer_debt' => 0,
                    'store_id' => $request->store,
                    'grand_total' => 0,
                ]);
            }
            $balance = BalanceStores::where('store_id', $request->store)->first();
            $store = Store::where('id', $request->store)->first();
            $history = BalanceStoreHistory::where('balance_store_id', $balance->id)->orderBy('tgl', 'DESC')->get();
        }

        return view('pages.balance-store.index', compact('history', 'balance', 'store'));
    }

    public function transaction(Request $request)
    {
        $balance  = BalanceStores::where('id', $request->balance)->first();
        return view('pages.balance-store.transaksi', compact('balance'));
    }

    public function prosesTransaction(Request $request)
    {
        try {
            $balanceId = $request->balanceId;
            $type = $request->type;
            $dataBalance = BalanceStores::where('id', $balanceId)->first();
            if ($type === '1') {
                BalanceStores::where('id', $balanceId)->update([
                    'amount_in_cashier' => $dataBalance->amount_in_cashier + $request->amount,
                    'grand_total' => $dataBalance->grand_total + $request->amount
                ]);

                BalanceStoreHistory::create([
                    'balance_store_id' => $balanceId,
                    'amount' => $request->amount,
                    'balance_start' => $dataBalance->grand_total,
                    'balance_end' => $dataBalance->grand_total + $request->amount,
                    'type' => $type,
                    'description' => $request->note,
                    'tgl' => date('Ymd')
                ]);
            } else if ($type === '2') {
                BalanceStores::where('id', $balanceId)->update([
                    'amount_customer_debt' => $dataBalance->amount_customer_debt + $request->amount,
                    'grand_total' => $dataBalance->grand_total + $request->amount
                ]);

                BalanceStoreHistory::create([
                    'balance_store_id' => $balanceId,
                    'amount' => $request->amount,
                    'balance_start' => $dataBalance->grand_total,
                    'balance_end' => $dataBalance->grand_total + $request->amount,
                    'type' => $type,
                    'description' => $request->note,
                    'tgl' => date('Ymd')
                ]);
            } else {
                if ($request->sumber === '1') {
                    if ($request->amount > $dataBalance->amount_in_hand) {
                        FlashData::danger_alert('Jumlah yang anda masukan melebihi saldo yang dipegang atau diatm');
                        return redirect()->back();
                    }
                    BalanceStores::where('id', $balanceId)->update([
                        'amount_in_hand' => $dataBalance->amount_in_hand - $request->amount,
                        'grand_total' => $dataBalance->grand_total - $request->amount
                    ]);

                    BalanceStoreHistory::create([
                        'balance_store_id' => $balanceId,
                        'amount' => $request->amount,
                        'balance_start' => $dataBalance->grand_total,
                        'balance_end' => $dataBalance->grand_total - $request->amount,
                        'type' => $type,
                        'description' => $request->note,
                        'tgl' => date('Ymd')
                    ]);
                } else if ($request->sumber === '2') {
                    if ($request->amount > $dataBalance->amount_in_cashier) {
                        FlashData::danger_alert('Jumlah yang anda masukan melebihi saldo yang dikasir');
                        return redirect()->back();
                    }
                    BalanceStores::where('id', $balanceId)->update([
                        'amount_in_cashier' => $dataBalance->amount_in_cashier - $request->amount,
                        'grand_total' => $dataBalance->grand_total - $request->amount
                    ]);

                    BalanceStoreHistory::create([
                        'balance_store_id' => $balanceId,
                        'amount' => $request->amount,
                        'balance_start' => $dataBalance->grand_total,
                        'balance_end' => $dataBalance->grand_total - $request->amount,
                        'type' => $type,
                        'description' => $request->note,
                        'tgl' => date('Ymd')
                    ]);
                } else {
                    if ($request->amount > $dataBalance->amount_customer_debt) {
                        FlashData::danger_alert('Jumlah yang anda masukan melebihi saldo deposit customer');
                        return redirect()->back();
                    }
                    BalanceStores::where('id', $balanceId)->update([
                        'amount_customer_debt' => $dataBalance->amount_customer_debt - $request->amount,
                        'grand_total' => $dataBalance->grand_total - $request->amount
                    ]);

                    BalanceStoreHistory::create([
                        'balance_store_id' => $balanceId,
                        'amount' => $request->amount,
                        'balance_start' => $dataBalance->grand_total,
                        'balance_end' => $dataBalance->grand_total - $request->amount,
                        'type' => $type,
                        'description' => $request->note,
                        'tgl' => date('Ymd')
                    ]);
                }
            }

            FlashData::success_alert('Berhasil transaksi balance store');
            return redirect()->route('cash.balance_store');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
