<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::with('store')->get();

        return view('pages.category.index', compact('category'));
    }


    public function create()
    {
        $store = Store::all();
        return view('pages.category.create', compact('store'));
    }

    public function store(Request $request)
    {
        try {
            Category::create($request->all());

            FlashData::success_alert('Berhasil menambahkan data kategori');
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('categories.input_category');
        }
    }

    public function show($idCategory)
    {
        try {
            $category = Category::with('store')->where('id', $idCategory)->first();
            $store = Store::all();

            return view('pages.category.edit', compact('category', 'store'));
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('categories.index');
        }
    }

    public function update(Request $request, $idCategory)
    {
        try {
            Category::where('id', $idCategory)->update(['name' => $request->name, 'store_id' => $request->store_id]);
            FlashData::success_alert('Berhasil merubah data kategori');
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Category::where('id', $request->idCategory)->delete();
            FlashData::success_alert('Berhasil menghapus data');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
        }

        return redirect()->route('categories.index');
    }
}
