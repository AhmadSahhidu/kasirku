<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceStoreHistory;
use App\Models\BalanceStores;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderCart;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderPayment;
use App\Models\Store;
use App\Models\SubProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $supplier = Supplier::all();
        $cart = [];
        $product = [];
        $total = 0;
        $balance = [];
        if ($request->supplier) {
            $cart = PurchaseOrderCart::where('supplier_id', $request->supplier)->where('user_id', auth()->user()->id)->get();
            $supplier = Supplier::where('id', $request->supplier)->first();
            if (!$supplier) {
                FlashData::danger_alert('Supplier tidak ditemukan');
                return redirect()->route('purchase.index');
            }
            $product = Product::where('supplier_id', $request->supplier)->get();
            foreach ($cart as $items) {
                $total += $items->purchase_price * $items->qty;
            }
            $balance = BalanceStores::where('store_id', $supplier->store_id)->first();
        }
        return view('pages.purchase-order.index', compact('supplier', 'cart', 'product', 'total', 'balance'));
    }

    public function validationSupplierPurchaseOrder(Request $request)
    {
        // Validasi input
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:20',
            'supplier_address' => 'nullable|string|max:255',
        ]);

        $supplier = null;

        // Jika supplier_id kosong, maka buat supplier baru
        if (empty($request->supplier_id)) {
            $stores = Store::first();

            // Cek apakah supplier dengan nama yang sama sudah ada
            $existingSupplier = Supplier::where('name', $request->supplier_name)->first();

            if ($existingSupplier) {
                $supplier = $existingSupplier;
            } else {
                // Buat supplier baru jika belum ada
                $supplier = Supplier::create([
                    'id' => generateUuid(),
                    'name' => $request->supplier_name,
                    'phone' => $request->supplier_phone,
                    'address' => $request->supplier_address,
                    'sales_name' => '',
                    'sales_phone' => '',
                    'store_id' => $stores->id,
                ]);
            }
        } else {
            // Jika supplier_id ada, cari supplier berdasarkan id tersebut
            $supplier = Supplier::where('id', $request->supplier_id)->first();

            // Pastikan supplier ditemukan
            if (!$supplier) {
                return redirect()->back()->withErrors(['supplier_id' => 'Supplier tidak ditemukan.']);
            }
        }

        // Redirect ke route purchase index dengan query supplier=supplier_id
        return redirect()->route('purchase.index', ['supplier' => $supplier->id]);
    }


    public function prosesAddCart(Request $request)
    {
        try {
            $productId = $request->product_id;
            $supplier = Supplier::where('id', $request->supplierId)->first();
            if (!$productId) {
                $cartCheck = PurchaseOrderCart::where('name_product', $request->product_name)->first();
                if ($cartCheck) {
                    PurchaseOrderCart::where('id', $cartCheck->id)->update(['qty' => $cartCheck->qty + $request->qty]);
                    return redirect()->back();
                }
                $category = Category::create([
                    'id' => generateUuid(),
                    'store_id' => $supplier->id,
                    'name' => $request->category_name
                ]);

                PurchaseOrderCart::create([
                    'supplier_id' => $request->supplierId,
                    'name_product' => $request->product_name,
                    'size' => $request->size,
                    'category_id' => $category->id,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price,
                    'qty' => $request->qty,
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $product = Product::where('id', $productId)->first();
                $cartCheck = PurchaseOrderCart::where('product_id', $product->id)->first();
                if ($cartCheck) {
                    PurchaseOrderCart::where('id', $cartCheck->id)->update(['qty' => $cartCheck->qty + $request->qty]);
                    return redirect()->back();
                }
                PurchaseOrderCart::create([
                    'supplier_id' => $product->supplier_id,
                    'name_product' => $product->name,
                    'product_id' => $product->id,
                    'size' => $product->size,
                    'category_id' => $product->category_id,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price,
                    'qty' => $request->qty,
                    'user_id' => auth()->user()->id,
                ]);
            }

            FlashData::success_alert('Berhasil menambahkan barang ke list order');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
        }
        return redirect()->back();
    }

    public function deleteListCartOrder($cartId)
    {
        try {
            PurchaseOrderCart::where('id', $cartId)->delete();
            FlashData::success_alert('Berhasil menghapus barang order');
            return redirect()->back();
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function prosesOrder(Request $request)
    {
        try {
            $supplier = Supplier::where('id', $request->supplier_id)->first();
            $balance = BalanceStores::where('store_id', $supplier->store_id)->first();
            if ($request->payment_method === 'cash') {
                if ($request->sumber_dana === 'cashier') {
                    if ($request->total > $balance->amount_in_cashier) {
                        FlashData::danger_alert('Balance Kasir Tidak mencukupi');
                        return redirect()->back();
                    }
                } else {
                    if ($request->total > $balance->amount_in_hand) {
                        FlashData::danger_alert('Balance Atm Tidak mencukupi');
                        return redirect()->back();
                    }
                }
            }

            $purchaseOrderCart = PurchaseOrderCart::with('product')->where('user_id', auth()->user()->id)->where('supplier_id', $supplier->id)->get();
            if ($purchaseOrderCart->count() === 0) {
                FlashData::danger_alert('Tidak ada list order barang pembelian');
                return redirect()->back();
            }
            $purchaseOrder = PurchaseOrder::create([
                'id' => generateUuid(),
                'number' => 'PO-' . date('YmdHis'),
                'store_id' => $supplier->store_id,
                'supplier_id' => $supplier->id,
                'grand_total' => $request->grand_total,
                'due_date' => $request->due_date,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'cash' ? 2 : 1,
                'user_id' => auth()->user()->id,
            ]);

            if ($request->payment_method === 'cash') {
                if ($request->sumber_dana === 'cashier') {
                    BalanceStores::where('id', $balance->id)->update([
                        'amount_in_cashier' => $balance->amount_in_cashier - $request->total,
                        'grand_total' => $balance->grand_total - $request->total
                    ]);

                    BalanceStoreHistory::create([
                        'balance_store_id' => $balance->id,
                        'amount' => $request->total,
                        'balance_start' => $balance->grand_total,
                        'balance_end' => $balance->grand_total - $request->total,
                        'sumber' => 1,
                        'tgl' => date('Y-m-d'),
                        'type' => 3,
                        'description' => 'Pembayaran pada pembelian order ' . $purchaseOrder->number . '.',
                    ]);
                } else {

                    BalanceStores::where('id', $balance->id)->update([
                        'amount_in_hand' => $balance->amount_in_hand - $request->total,
                        'grand_total' => $balance->grand_total - $request->total
                    ]);
                    BalanceStoreHistory::create([
                        'balance_store_id' => $balance->id,
                        'amount' => $request->total,
                        'balance_start' => $balance->grand_total,
                        'balance_end' => $balance->grand_total - $request->total,
                        'tgl' => date('Y-m-d'),
                        'sumber' => 2,
                        'type' => 3,
                        'description' => 'Pembayaran pada pembelian order ' . $purchaseOrder->number . '.',
                    ]);
                }
            }


            foreach ($purchaseOrderCart as $items) {
                if ($items->product_id) {
                    $product = SubProduct::where('product_id', $items->product_id)->orderby('id', 'DESC')->first();
                    if ($product->purchase_price === $items->purchase_price) {
                        SubProduct::where('id', $product->id)->update([
                            'stock' => $items->stock
                        ]);
                    } else {
                        SubProduct::create([
                            'product_id' => $items->product_id,
                            'purchase_price' => $items->purchase_price,
                            'selling_price' => $items->selling_price,
                            'stock' => $items->qty
                        ]);
                    }
                    PurchaseOrderItems::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $items->product_id,
                        'selling_price' => $items->selling_price,
                        'stock' => $items->qty,
                        'purchase_price' => $items->purchase_price,
                        'grand_total' => $items->purchase_price * $items->qty
                    ]);
                } else {
                    $product = Product::create([
                        'id' => generateUuid(),
                        'name' => $items->name_product,
                        'number' => 'PDT' . date('YmdHis'),
                        'supplier_id' => $items->supplier_id,
                        'category_id' => $items->category_id,
                        'size' => $items->size,
                        'seri' => '',
                        'satuan' => '',
                        'user_id' => auth()->user()->id,
                    ]);

                    SubProduct::create([
                        'product_id' => $product->id,
                        'purchase_price' => $items->purchase_price,
                        'selling_price' => $items->selling_price,
                        'stock' => $items->qty
                    ]);
                    PurchaseOrderItems::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $product->id,
                        'selling_price' => $items->selling_price,
                        'stock' => $items->qty,
                        'purchase_price' => $items->purchase_price,
                        'grand_total' => $items->purchase_price * $items->qty
                    ]);
                }
            }

            PurchaseOrderCart::where('supplier_id', $supplier->id)->where('user_id', auth()->user()->id)->delete();

            FlashData::success_alert('Berhasil membuat order pembelian');
            return redirect()->route('purchase.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function listPurchaseOrder(Request $request)
    {
        $purchaseOrder = [];
        $supplier = null;
        $suppliers = Supplier::all();
        if ($request->supplier) {
            $purchaseOrder = PurchaseOrder::with('supplier')->where('supplier_id', $request->supplier)->get();
            $supplier = Supplier::where('id', $request->supplier)->first();
        }

        return view('pages.purchase-order.list-order-pembelian', compact('purchaseOrder', 'suppliers', 'supplier'));
    }

    public function detailPurchaseOrder($purchaseId)
    {
        $purchaseOrder = PurchaseOrder::where('id', $purchaseId)->first();
        $supplier = Supplier::where('id', $purchaseOrder->supplier_id)->first();
        $purchaseOrderItems = PurchaseOrderItems::with('product')->where('purchase_order_id', $purchaseOrder->id)->get();

        return view('pages.purchase-order.detail-order-pembelian', compact('purchaseOrder', 'supplier', 'purchaseOrderItems'));
    }

    public function confirmPaymentPurchaseOrder($purchaseId)
    {
        $purchaseOrder = PurchaseOrder::where('id', $purchaseId)->first();
        $supplier = Supplier::where('id', $purchaseOrder->supplier_id)->first();
        $purchaseOrderItems = PurchaseOrderItems::where('purchase_order_id', $purchaseOrder->id)->get();

        $balanceStore = BalanceStores::where('store_id', $purchaseOrder->store_id)->first();

        return view('pages.purchase-order.konfirmasi-pembayaran', compact('purchaseOrder', 'supplier', 'purchaseOrderItems', 'balanceStore'));
    }

    public function prosesConfirmPayment(Request $request)
    {
        try {
            $purchaseOrder = PurchaseOrder::where('id', $request->purchase_id)->first();
            $balanceStore = BalanceStores::where('store_id', $purchaseOrder->store_id)->first();

            $balanceStoreCashier = $balanceStore->amount_in_cashier;
            $balanceStorehand = $balanceStore->amount_in_hand;
            $balanceStoreCustomerDepo = $balanceStore->amount_customer_debt;

            if ($request->amount > $request->amount_purchase_order) {
                FlashData::danger_alert('Nominal yang anda masukkan melebihi jumlah pembayaran seharusnya');
                return redirect()->back();
            }

            //validasi balance
            if ($request->sumber_dana === 'cashier') {
                if ($request->amount_purchase_order >= $balanceStoreCashier) {
                    FlashData::danger_alert('Saldo didalam kasir tidak mencukupi untuk melakukan pembayaran ini');
                    return redirect()->back();
                }

                BalanceStores::where('id', $balanceStore->id)->update([
                    'amount_in_cashier' => $balanceStore->amount_in_cashier - $request->amount,
                    'grand_total' => $balanceStore->grand_total - $request->amount
                ]);
            } else if ($request->sumber_dana === 'hand') {
                if ($request->amount_purchase_order >= $balanceStorehand) {
                    FlashData::danger_alert('Saldo didalam ATM / Dipegang sendiri tidak mencukupi untuk melakukan pembayaran ini');
                    return redirect()->back();
                }
                BalanceStores::where('id', $balanceStore->id)->update([
                    'amount_in_hand' => $balanceStore->amount_in_hand - $request->amount,
                    'grand_total' => $balanceStore->grand_total - $request->amount
                ]);
            } else {
                if ($request->amount_purchase_order >= $balanceStoreCustomerDepo) {
                    FlashData::danger_alert('Saldo deposit customer tidak mencukupi untuk melakukan pembayaran ini');
                    return redirect()->back();
                }
                BalanceStores::where('id', $balanceStore->id)->update([
                    'amount_customer_debt' => $balanceStore->amount_customer_debt - $request->amount,
                    'grand_total' => $balanceStore->grand_total - $request->amount
                ]);
            }

            BalanceStoreHistory::create([
                'balance_store_id' => $balanceStore->id,
                'amount' => $request->amount_purchase_order,
                'balance_start' => $balanceStore->grand_total,
                'balance_end' => $balanceStore->grand_total - $request->amount_purchase_order,
                'type' => 3,
                'description' => 'Pembayaran pada order pembelian ' . $purchaseOrder->number,
                'tgl' => date('Ymd')
            ]);

            PurchaseOrderPayment::create([
                'purchase_order_id' => $purchaseOrder->id,
                'sumber_dana' => $request->sumber_dana,
                'amount_purchase_order' => $request->amount_purchase_order,
                'amount' => $request->amount
            ]);

            CashFlow::create([
                'number' => 'CSF-' . date('YmdHis'),
                'type_cash' => 2,
                'amount' => $request->amount,
                'note' => 'Pembayaran pada order pembelian ' . $purchaseOrder->number,
                'store_id' => $purchaseOrder->store_id,
            ]);

            PurchaseOrder::where('id', $purchaseOrder->id)->update([
                'status' => 2
            ]);

            FlashData::success_alert('Berhasil melakukan pembayaran pada order pembelian' . $purchaseOrder->number);
            return redirect()->route('purchase.list_order_pembelian');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
