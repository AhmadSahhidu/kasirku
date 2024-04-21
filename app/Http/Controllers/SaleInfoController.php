<?php

namespace App\Http\Controllers;

use App\Models\SaleInfo;
use App\Models\Sales;
use Illuminate\Http\Request;

class SaleInfoController extends Controller
{
    public function index($idSale)
    {
        $sale = Sales::with('items', 'user', 'store', 'customer')->where('id', $idSale)->first();
        $total = 0;
        $saleInfo = SaleInfo::where('sale_id', $sale->id)->first();
        // $customer = Customer::all();
        foreach ($sale->items as $items) {
            $total += $items->product->selling_price * $items->qty;
        }
        return view('pages.cashiers.sale-info.index', compact('sale', 'total', 'saleInfo'));
    }

    public function receipF($idSale)
    {
        $sale = Sales::with('items', 'user', 'store', 'customer')->where('id', $idSale)->first();
        $total = 0;
        $saleInfo = SaleInfo::where('sale_id', $sale->id)->first();
        // $customer = Customer::all();
        foreach ($sale->items as $items) {
            $total += $items->product->selling_price * $items->qty;
        }
        return view('pages.cashiers.sale-info.receip-f',  compact('sale', 'total', 'saleInfo'));
    }

    public function sellingNote($idSale)
    {
        $sale = Sales::with('items', 'user', 'store', 'customer')->where('id', $idSale)->first();
        $total = 0;
        $saleInfo = SaleInfo::where('sale_id', $sale->id)->first();
        // $customer = Customer::all();
        foreach ($sale->items as $items) {
            $total += $items->product->selling_price * $items->qty;
        }
        return view('pages.cashiers.sale-info.selling-note',  compact('sale', 'total', 'saleInfo'));
    }
}
