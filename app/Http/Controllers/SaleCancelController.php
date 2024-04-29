<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceCustomer;
use App\Models\HistoryBalanceCustomer;
use App\Models\Product;
use App\Models\SaleItems;
use App\Models\Sales;
use App\Models\StockOpname;
use Illuminate\Http\Request;

class SaleCancelController extends Controller
{
    public function index()
    {
        if (request('sale_id')) {
            $sales = Sales::with('customer', 'user', 'store')->where('id', request('sale_id'))->first();
        } else {
            $sales = Sales::with('customer', 'user', 'store')->get();
        }

        return view('pages.sale-cancel.index', compact('sales'));
    }

    public function cancelTransaction($idSale)
    {
        try {
            $sale = Sales::with('items')->where('id', $idSale)->first();
            $generateNumber = 'STP' . date('YmsHis');

            foreach ($sale->items as $item) {
                StockOpname::create([
                    'number' => $generateNumber,
                    'type' => 1,
                    'stock_before' => $item->product->stock,
                    'qty' => $item->qty,
                    'product_id' => $item->product_id,
                    'stock_after' => $item->product->stock + $item->qty,
                    'note' => 'Pembatalan transaksi pada ' . $sale->number,
                    'user_id' => auth()->user()->id,
                    'store_id' => $sale->store_id,
                ]);
                Product::where('id', $item->product_id)->update(['stock' => $item->product->stock + $item->qty]);
                SaleItems::where('id', $item->id)->delete();
            }
            if ($sale->payment_method === 4) {
                if ($sale->grand_total > 0) {
                    $balance = BalanceCustomer::where('customer_id', $sale->customer_id)->first();
                    if ($balance) {
                        BalanceCustomer::where('id', $balance->id)->update([
                            'nominal' => $balance->nominal + $sale->grand_total
                        ]);
                        HistoryBalanceCustomer::create([
                            'number' => 'HBC-' . date('YmdHis'),
                            'status' => 1,
                            'note' => 'Pembatalan saldo pada transaksi ' . $sale->number,
                            'nominal' => $sale->grand_total,
                            'start_balance' => $balance->nominal,
                            'end_balance' => $balance->nominal + $sale->grand_total,
                            'balance_id' => $balance->id,
                        ]);
                    }
                }
            }
            Sales::where('id', $sale->id)->delete();

            FlashData::success_alert('Berhasil membatalkan transaksi ' . $sale->number);
            return redirect()->route('sale_cancel.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
