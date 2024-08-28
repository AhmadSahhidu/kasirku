<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Cash;
use App\Models\CashFlow;
use App\Models\Store;
use Illuminate\Http\Request;
use Throwable;

class CashController extends Controller
{
    public function index()
    {
        $roleUser = userRoleName();
        $store = Store::all();
        if ($roleUser === 'Super Admin') {
            $cashIn = Cash::with('store', 'user')
                ->where('type_cash', 1)
                ->get();
            $countIn = Cash::with('store', 'user')
                ->where('type_cash', 1)
                ->sum('amount');
            $cashModal = Cash::with('store', 'user')
                ->where('type_cash', 2)
                ->where('type_cash_out', 1)
                ->get();
            $countModal = Cash::with('store', 'user')
                ->where('type_cash', 2)
                ->where('type_cash_out', 1)
                ->sum('amount');
            $cashOperasional = Cash::with('store', 'user')
                ->where('type_cash', 2)
                ->where('type_cash_out', 2)
                ->get();
            $countOperasional = Cash::with('store', 'user')
                ->where('type_cash', 2)
                ->where('type_cash_out', 2)
                ->sum('amount');
        } else {
            $storeName = userStore();
            $store = Store::where('name', $storeName)->first();
            $cashIn = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 1)
                ->get();
            $countIn = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 1)
                ->sum('amount');
            $cashModal = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 2)
                ->where('type_cash_out', 1)
                ->get();

            $countModal = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 2)
                ->where('type_cash_out', 1)
                ->sum('amount');
            $cashOperasional = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 2)
                ->where('type_cash_out', 2)
                ->get();
            $countOperasional = Cash::with('store', 'user')
                ->where('store_id', $store->id)
                ->where('type_cash', 2)
                ->where('type_cash_out', 2)
                ->sum('amount');
        }


        return view('pages.cash.index', compact('cashIn', 'cashModal', 'cashOperasional', 'countModal', 'countOperasional', 'countIn', 'store'));
    }

    public function create()
    {
        $roleUser = userRoleName();
        if ($roleUser === 'Super Admin') {

            $stores = Store::all();
        } else {
            $stores = null;
        }
        return view('pages.cash.create', compact('stores'));
    }

    public function store(Request $request)
    {
        try {
            $roleUser = userRoleName();
            if ($roleUser === 'Super Admin') {
                $cash = Cash::create([
                    'id' => generateUuid(),
                    'number' => 'CH' . date('YmdHis'),
                    'amount' => $request->amount,
                    'note' => $request->note,
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out,
                    'user_id' => auth()->user()->id,
                    'store_id' => $request->store_id,
                    'tgl' => $request->tgl,
                ]);
                CashFlow::create([
                    'number' => 'CSF-' . date('YmdHis'),
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out,
                    'amount' => $request->amount,
                    'cash_id' => $cash->id,
                    'note' => 'Arus Kas : ' . $request->note,
                    'store_id' => $request->store_id
                ]);
            } else {
                $storeUser = userStore();
                $store = Store::where('name', $storeUser)->first();
                $cash  = Cash::create([
                    'id' => generateUuid(),
                    'number' => 'CH' . date('YmdHis'),
                    'amount' => $request->amount,
                    'note' => $request->note,
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out ?? null,
                    'user_id' => auth()->user()->id,
                    'store_id' => $store->id,
                    'tgl' => $request->tgl
                ]);
                CashFlow::create([
                    'number' => 'CSF-' . date('YmdHis'),
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out,
                    'cash_id' => $cash->id,
                    'amount' => $request->amount,
                    'note' => 'Arus Kas : ' . $request->note,
                    'store_id' => $store->id
                ]);
            }
            FlashData::success_alert('Berhasil menambahkan data arus kas');
            return redirect()->route('cash.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function show($cashId)
    {
        $cash = Cash::where('id', $cashId)->first();
        $roleUser = userRoleName();
        if ($roleUser === 'Super Admin') {
            $stores = Store::all();
        } else {
            $stores = null;
        }
        return view('pages.cash.edit', compact('stores', 'cash'));
    }

    public function update(Request $request, $cashId)
    {
        try {
            $roleUser = userRoleName();
            if ($roleUser === 'Super Admin') {
                $cash = Cash::where('id', $cashId)->update([
                    'amount' => $request->amount,
                    'note' => $request->note,
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out ?? null,
                    'store_id' => $request->store_id,
                    'tgl' => $request->tgl
                ]);
                CashFlow::where('cash_id', $cashId)->update([
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out,
                    'amount' => $request->amount,
                    'note' => 'Arus Kas : ' . $request->note,
                    'store_id' => $request->store_id
                ]);
            } else {
                Cash::where('id', $cashId)->update([
                    'amount' => $request->amount,
                    'note' => $request->note,
                    'type_cash' => $request->type_cash,
                    'tgl' => $request->tgl,
                    'type_cash_out' => $request->type_cash_out ?? null,
                ]);
                CashFlow::where('cash_id', $cashId)->update([
                    'type_cash' => $request->type_cash,
                    'type_cash_out' => $request->type_cash_out,
                    'amount' => $request->amount,
                    'note' => 'Arus Kas : ' . $request->note,
                ]);
            }
            FlashData::success_alert('Berhasil merubah data arus kas');
            return redirect()->route('cash.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Cash::where('id', $request->idCash)->delete();
            CashFlow::where('cash_id', $request->idCash)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
