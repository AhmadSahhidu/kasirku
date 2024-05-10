<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Throwable;

class SupplierController extends Controller
{
    public function index()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $supplier = Supplier::with('store')->get();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $supplier = Supplier::with('store')->where('store_id', $stores->id)->get();
        }

        return view('pages.supplier.index', compact('supplier'));
    }

    public function create()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $store = Store::all();
        } else {
            $userStore = userStore();
            $store = Store::where('name', $userStore)->get();
        }

        return view('pages.supplier.create', compact('store'));
    }

    public function store(Request $request)
    {
        try {
            Supplier::create($request->all());
            FlashData::success_alert('Berhasil menambahkan supplier baru');
            return redirect()->route('supplier.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function show($idSupplier)
    {
        $supplier = Supplier::with('store')->where('id', $idSupplier)->first();

        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $store = Store::all();
        } else {
            $userStore = userStore();
            $store = Store::where('name', $userStore)->get();
        }

        return view('pages.supplier.edit', compact('supplier', 'store'));
    }

    public function update(Request $request, $idSupplier)
    {
        try {
            Supplier::where('id', $idSupplier)->update(['name' => $request->name, 'phone' => $request->phone, 'sales_name' => $request->sales_name, 'sales_phone' => $request->sales_phone, 'address' => $request->address, 'store_id' => $request->store_id]);
            FlashData::success_alert('Berhasil merubah data supplier');

            return redirect()->route('supplier.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Supplier::where('id', $request->idSupplier)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return redirect()->route('supplier.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('supplier.index');
        }
    }
}
