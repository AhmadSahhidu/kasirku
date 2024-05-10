<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Imports\ProductImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $product = Product::with('store', 'supplier', 'brand', 'category')->get();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $product = Product::with('store', 'supplier', 'brand', 'category')->where('store_id', $stores->id)->get();
        }

        return view('pages.product.index', compact('product'));
    }

    public function create()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $supplier = Supplier::all();
            $brand = Brand::all();
            $store = Store::all();
            $category = Category::all();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $supplier = Supplier::where('store_id', $stores->id)->get();
            $brand = Brand::where('store_id', $stores->id)->get();
            $store = Store::where('id', $stores->id)->get();
            $category = Category::where('store_id', $stores->id)->get();
        }

        return view('pages.product.create', compact('supplier', 'brand', 'store', 'category'));
    }

    public function store(Request $request)
    {
        try {
            $generateNumber = 'CPD' . date('YmdHis');

            Product::create([
                'number' => $generateNumber,
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'size' => $request->size,
                'seri' => $request->seri,
                'satuan' => $request->satuan,
                'stock' => $request->stock,
                'stock_minimum' => $request->stock_minimum,
                'purchase_price' => $request->purchase_price,
                'selling_price' => $request->selling_price,
                'store_id' => $request->store_id,
                'user_id' => auth()->user()->id
            ]);

            FlashData::success_alert('Berhasil menambahkan produk baru');
            return redirect()->route('product.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function show($idProduct)
    {
        $product = Product::where('id', $idProduct)->first();

        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $supplier = Supplier::all();
            $brand = Brand::all();
            $store = Store::all();
            $category = Category::all();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $supplier = Supplier::where('store_id', $stores->id)->get();
            $brand = Brand::where('store_id', $stores->id)->get();
            $store = Store::where('id', $stores->id)->get();
            $category = Category::where('store_id', $stores->id)->get();
        }

        return view('pages.product.edit', compact('supplier', 'brand', 'store', 'category', 'product'));
    }

    public function update(Request $request, $idProduct)
    {
        try {
            Product::where('id', $idProduct)->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'size' => $request->size,
                'seri' => $request->seri,
                'satuan' => $request->satuan,
                'stock' => $request->stock,
                'stock_minimum' => $request->stock_minimum,
                'purchase_price' => $request->purchase_price,
                'selling_price' => $request->selling_price,
                'store_id' => $request->store_id,
            ]);

            FlashData::success_alert('Berhasil merubah data produk');
            return redirect()->route('product.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Product::where('id', $request->idProduct)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return redirect()->route('product.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('product.index');
        }
    }

    public function importProduct(Request $request)
    {
        try {
            $request->validate(['file' => 'required|mimes:csv,xls,xlsx']);

            $file = $request->file('file');
            $nama_file = rand() . $file->getClientOriginalName();
            $file->move('import/product', $nama_file);
            Excel::import(new ProductImport, public_path('/import/product/' . $nama_file));

            FlashData::success_alert('Berhasil melakukan import product');
            return redirect()->route('product.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
