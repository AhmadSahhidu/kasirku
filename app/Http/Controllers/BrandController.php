<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Brand;
use App\Models\Store;
use Illuminate\Http\Request;
use Throwable;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::with('store')->get();
        error_log($brand);
        return view('pages.brand.index', compact('brand'));
    }

    public function create()
    {
        $store = Store::all();
        return view('pages.brand.create', compact('store'));
    }

    public function store(Request $request)
    {
        try {
            Brand::create($request->all());

            FlashData::success_alert('Berhasil menambahkan data merek');
            return redirect()->route('brand.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('brand.input_brand');
        }
    }

    public function show($idBrand)
    {
        try {
            $brand = Brand::with('store')->where('id', $idBrand)->first();
            $store = Store::all();

            return view('pages.brand.edit', compact('brand', 'store'));
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('brand.index');
        }
    }

    public function update(Request $request, $idBrand)
    {
        try {
            Brand::where('id', $idBrand)->update(['name' => $request->name, 'store_id' => $request->store_id]);
            FlashData::success_alert('Berhasil merubah data merek');
            return redirect()->route('brand.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Brand::where('id', $request->idBrand)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return redirect()->route('brand.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('brand.index');
        }
    }
}
