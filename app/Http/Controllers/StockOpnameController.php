<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $stock = StockOpname::with('product', 'user', 'store')->orderBy('created_at', 'DESC')->get();

        return view('pages.product.stock_opname.index', compact('stock'));
    }

    public function create()
    {
        $product = Product::all();

        return view('pages.product.stock_opname.create', compact('product'));
    }

    public function store(Request $request)
    {
        try {
            $product = Product::where('id', $request->product_id)->first();
            $stok = null;
            $generateNumber = 'STP' . date('YmsHis');
            if ($request->type === '1') {
                $stok = $product->stock + $request->qty;
            } else {
                $stok = $product->stock - $request->qty;
            }

            StockOpname::create([
                'number' => $generateNumber,
                'type' => $request->type,
                'stock_before' => $product->stock,
                'qty' => $request->qty,
                'product_id' => $product->id,
                'stock_after' => $stok,
                'note' => $request->note ?? '-',
                'user_id' => auth()->user()->id,
                'store_id' => $product->store_id,
            ]);

            Product::where('id', $product->id)->update([
                'stock' => $stok
            ]);

            FlashData::success_alert('Berhasil melakukan stock opname');
            return redirect()->route('product.stock_opname.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
