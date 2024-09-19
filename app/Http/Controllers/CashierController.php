<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceCustomer;
use App\Models\BalanceStoreHistory;
use App\Models\BalanceStores;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\RequestDiskon;
use App\Models\Sales;
use App\Models\Store;
use App\Models\SubProduct;
use App\Services\CashFlowService;
use App\Services\CashierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{

    public function __construct(private CashierService $cashierService, private CashFlowService $cashFlowService) {}

    public function index()
    {
        $aksesKasir = validationAkses('kasir');
        $roleuser = userRoleName();
        if (!$aksesKasir && $roleuser != 'Super Admin') {
            return redirect()->route('dashboard');
        }
        if ($roleuser === 'Super Admin') {
            $product = SubProduct::with('product')->get();

            $customer = Customer::all();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $customer = Customer::where('store_id', $stores->id)->get();
            $product = Product::with('store', 'supplier', 'brand', 'category')->where('store_id', $stores->id)->get();
        }

        $diskon = RequestDiskon::where(['sale_id' => null, 'user_id' => auth()->user()->id])->first();
        $cart = Cart::with('product', 'user')->get();
        $total = 0;
        foreach ($cart as $items) {
            $total += $items->product->selling_price * $items->qty;
        }
        return view('pages.cashiers.index', compact('product', 'cart', 'total', 'customer', 'diskon'));
    }

    public function storeCart(Request $request)
    {
        try {
            $product = SubProduct::where('id', $request->sub_product_id)->first();
            $generateNumberCart = date('YmdHis');
            $stores = Store::first();
            $getCart = Cart::where('product_id', $product->id)->where('user_id', auth()->user()->id)->first();
            if ($getCart) {
                $totalQty = $getCart->qty + $request->qty;
                if ($totalQty > $product->stock) {
                    FlashData::danger_alert('Total stok tidak mencukupi');
                    return redirect()->route('cashier.index');
                }
                Cart::where('id', $getCart->id)->update(['qty' => $totalQty]);
            } else {
                if ($request->qty > $product->stock) {
                    FlashData::danger_alert('Total stok tidak mencukupi');
                    return redirect()->route('cashier.index');
                }
                Cart::create([
                    'number' => $generateNumberCart,
                    'product_id' => $product->id,
                    'qty' => $request->qty,
                    'store_id' => $product->product->store_id ?? $stores->id,
                    'user_id' => auth()->user()->id,
                ]);
            }
            FlashData::success_alert('Berhasil menambahkan produk kedalam keranjang');
            return redirect()->route('cashier.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function editQty(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->cart_id)->first();
            $product = SubProduct::where('id', $cart->product_id)->first();

            $totalQtyCart = $request->qty;
            if ($totalQtyCart > $product->stock) {
                FlashData::danger_alert('Total stok tidak mencukupi');
                return redirect()->route('cashier.index');
            }

            Cart::where('id', $cart->id)->update(['qty' => $totalQtyCart]);
            FlashData::success_alert('Berhasil merubah stok produk');
            return redirect()->route('cashier.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('cashier.index');
        }
    }

    public function deleteCart(Request $request)
    {
        try {
            Cart::where('id', $request->cart_id)->delete();

            return response()->json(['message' => 'Berhasil menghapus data keranjang', 'status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error']);
        }
    }

    public function storeSale(Request $request)
    {
        try {
            $cart = Cart::where('user_id', auth()->user()->id)->first();
            if (!$cart) {
                FlashData::danger_alert('Keranjang anda kosong, silahkan pilih produk terlebih dahulu');
                return redirect()->route('cashier.index');
            }



            $paymentMethod = (int) $request->payment_method;
            if ($paymentMethod === 4) {
                $balanceCustomer = BalanceCustomer::where('customer_id', $request->customer_id)->first();

                if ($request->grand_total > $balanceCustomer->nominal) {

                    FlashData::danger_alert('Saldo balance customer tidak mencukupi untuk melakukan pembayaran transaksi');
                    return redirect()->back();
                }
            }

            $sale = Sales::create([
                'id' => generateUuid(),
                'customer_id' => $request->customer_id,
                'total' => $request->total,
                'grand_total' => $request->grand_total,
                'discount' => $request->diskon ?? null,
                'payment_method' => $paymentMethod,
                'user_id' => auth()->user()->id,
                'store_id' => $cart->store_id
            ]);

            $diskon = RequestDiskon::where(['sale_id' => null, 'user_id' => auth()->user()->id])->first();
            if ($diskon !== null) {
                RequestDiskon::where(['sale_id' => null, 'user_id' => auth()->user()->id])->update(['sale_id' => $sale->id]);
            }

            $this->cashierService->createSaleItems($sale);
            $this->cashierService->historyStockProduct($sale);
            $this->cashierService->balanceCustomer($sale);
            $this->cashierService->paymentDebt($sale, $request);
            $this->cashierService->saleInfo($sale, $request);

            $this->cashFlowService->cashFlowInCashier($sale);

            $balanceStore = BalanceStores::where('store_id', $sale->store_id)->first();


            BalanceStoreHistory::create([
                'balance_store_id' => $balanceStore->id,
                'amount' => $request->grand_total,
                'balance_start' => $balanceStore->grand_total,
                'balance_end' => $balanceStore->grand_total + $request->grand_total,
                'type' => 4,
                'tgl' => date('Ymd'),
                'description' => 'Penjualan pada transaksi ' . $sale->number,
            ]);
            BalanceStores::where('id', $balanceStore->id)->update([
                'amount_in_cashier' => $balanceStore->amount_in_cashier + $request->grand_total,
                'grand_total' => $balanceStore->grand_total + $request->grand_total
            ]);

            Cart::where('user_id', auth()->user()->id)->delete();

            FlashData::success_alert('Transaksi berhasil dibuat');
            return redirect()->route('cashier.sale_info', $sale->id);
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            DB::rollBack();
            return redirect()->back();
        }
    }
}
