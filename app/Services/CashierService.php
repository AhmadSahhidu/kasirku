<?php

namespace App\Services;

use App\Models\BalanceCustomer;
use App\Models\Cart;
use App\Models\HistoryBalanceCustomer;
use App\Models\Product;
use App\Models\SaleDebtPayment;
use App\Models\SaleInfo;
use App\Models\SaleItem;
use App\Models\SaleItems;
use App\Models\Sales;
use App\Models\StockOpname;
use App\Models\Store;
use App\Models\SubProduct;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CashierService
{
    /**
     * @throws Exception
     */
    public function createSaleItems(Sales $sale): void
    {
        $carts = Cart::with('product', 'user')
            ->where('user_id', auth()->user()->id)
            ->get();

        foreach ($carts as $cart) {
            SaleItems::create([
                'sale_id' => $sale->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty,
                'purchase_price' => $cart->product->purchase_price,
                'price' => $cart->product->selling_price
            ]);
        }
    }

    public function historyStockProduct(Sales $sale): void
    {
        $carts = Cart::with('product', 'user')
            ->where('user_id', auth()->user()->id)
            ->get();
        $stores = Store::first();
        $generateNumberStockOpname = 'STP' . date('YmsHis');
        foreach ($carts as $cart) {
            SubProduct::where('id', $cart->product->id)->update([
                'stock' => $cart->product->stock - $cart->qty
            ]);
            StockOpname::create([
                'number' => $generateNumberStockOpname,
                'type' => 2,
                'stock_before' => $cart->product->stock,
                'qty' => $cart->qty,
                'product_id' => $cart->product->id,
                'stock_after' => $cart->product->stock - $cart->qty,
                'note' => 'Transaksi penjualan ' . $sale->number,
                'user_id' => auth()->user()->id,
                'store_id' => $cart->product->product->store_id ?? $stores->id,
            ]);
        }
    }

    public function balanceCustomer(Sales $sale): void
    {
        if ((int)$sale->payment_method === 4) {
            $balanceCustomer = BalanceCustomer::with('customer')->where('customer_id', $sale->customer_id)->first();

            BalanceCustomer::where('id', $balanceCustomer->id)->update([
                'nominal' => $balanceCustomer->nominal - $sale->grand_total
            ]);
            HistoryBalanceCustomer::create([
                'number' => 'HBC-' . date('YmdHis'),
                'status' => 2,
                'note' => 'Pembayaran transaksi pada pembelian ' . $sale->number,
                'nominal' => $sale->grand_total,
                'start_balance' => $balanceCustomer->nominal,
                'end_balance' => $balanceCustomer->nominal - $sale->grand_total,
                'balance_id' => $balanceCustomer->id,
            ]);
        }
    }

    public function paymentDebt(Sales $sale, HttpRequest $request): void
    {
        if ((int)$sale->payment_method === 3) {
            SaleDebtPayment::create([
                'sale_id' => $sale->id,
                'due_date' => $request->due_date,
                'paid' => 0,
                'remaining' => $sale->grand_total,
                'status' => 1
            ]);
        }
    }

    public function saleInfo(Sales $sale, HttpRequest $request): void
    {
        if ($sale->payment_method === 4) {
            SaleInfo::create([
                'sale_id' => $sale->id,
                'pay_amount' => $sale->grand_total,
                'change' => 0
            ]);
        } else if ($sale->payment_method === 3) {
            SaleInfo::create([
                'sale_id' => $sale->id,
                'pay_amount' => 0,
                'change' => 0
            ]);
        } else if ($sale->payment_method === 2) {
            SaleInfo::create([
                'sale_id' => $sale->id,
                'pay_amount' => $sale->grand_total,
                'change' => 0
            ]);
        } else {
            SaleInfo::create([
                'sale_id' => $sale->id,
                'pay_amount' => $request->nominal,
                'change' => $request->kembalian
            ]);
        }
    }
}
