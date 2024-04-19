<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class StoreController extends Controller
{
    public function index()
    {
        $store = Store::get();
        error_log($store);
        return view('pages.cabang.index', compact('store'));
    }

    public function create(Request $request)
    {
        return view('pages.cabang.create');
    }

    public function store(Request $request)
    {
        try {
            Store::create($request->all());
            FlashData::success_alert('Berhasil menambahkan data cabang store');
            return redirect()->route('cabang.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('cabang.input_store');
        }
    }

    public function edit($idStore)
    {
        $store = Store::where('id', $idStore)->first();
        return view('pages.cabang.edit', compact('store'));
    }

    public function update(Request $request, $idStore)
    {
        try {
            Store::where('id', $idStore)->update(['name' => $request->name]);
            FlashData::success_alert('Berhasil merubah data');
            return redirect()->route('cabang.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('cabang.edit_store');
        }
    }

    public function delete(Request $request)
    {
        try {
            Store::where('id', $request->idStore)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return redirect()->route('cabang.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('cabang.index');
        }
    }
}
