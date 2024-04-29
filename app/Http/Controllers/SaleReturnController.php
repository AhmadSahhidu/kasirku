<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\SaleItems;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Sales;
use App\Services\SaleReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleReturnController extends Controller
{
    public function __construct(private SaleReturnService $saleReturnService)
    {
    }

    public function index()
    {
        $sale_return = SaleReturn::with('sales', 'store', 'user')->get();
        return view('pages.sale-return.index', compact('sale_return'));
    }

    public function create()
    {
        try {
            $saleId = request('sale_id');
            if ($saleId) {
                $sale = Sales::with('customer')->where('id', $saleId)->first();
                if (!$sale) {
                    FlashData::danger_alert('Data transaksi tidak ditemukan..');
                    return redirect()->route('sale_returns.create_sale_return');
                }

                $validReturn = SaleReturn::where('sale_id', $saleId)->first();
                if ($validReturn) {
                    FlashData::danger_alert('Transaksi ini sudah pernah melakukan pengembalian barang..');
                    return redirect()->route('sale_returns.create_sale_return');
                }
            } else {
                $sale = Sales::with('customer')->get();
            }

            return view('pages.sale-return.create', compact('sale'));
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $saleId = $request->input('sale_id');
            $sale = Sales::where('id', $saleId)->first();
            $saleReturn = SaleReturn::create([
                'id' => generateUuid(),
                'sale_id' => $sale->id,
                'user_id' => auth()->id(),
                'status' => 1,
                'total' => 0,
                'store_id' => $sale->store_id,
            ]);

            foreach ($request->input('sale_item_id') as $saleItemId) {
                $quantity = (float) $request->input('quantity')[$saleItemId];
                if ($quantity == 0.0 or $quantity == '0' or $quantity == '' or $quantity == 0 or $quantity == '0.0') {
                    continue;
                }
                $saleItem = SaleItems::where('id', $saleItemId)->first();
                // dd($quantity);
                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItemId,
                    'qty' => (float) $request->input('quantity')[$saleItemId],
                    'total' => $saleItem->price * $request->input('quantity')[$saleItemId],
                ]);
            }
            $saleReturn->update([
                'total' => $saleReturn->returnItems->sum('total'),
            ]);

            // SaleReturnNotification::create($saleReturn);
            FlashData::success_alert('Berhasil membuat retur penjualan. Silahkan tunggu konfirmasi dari admin');
            DB::commit();

            return redirect()->route('sale_returns.detail_sale_return', $saleReturn->id);
        } catch (Throwable $th) {
            DB::rollBack();
            FlashData::danger_alert($th->getMessage());

            return redirect()->back();
        }
    }

    public function show($idReturn)
    {
        $sale_return = SaleReturn::with('returnItems', 'sales', 'user')->where('id', $idReturn)->first();

        return view('pages.sale-return.view', compact('sale_return'));
    }

    public function verifyReturn(Request $request)
    {
        DB::beginTransaction();
        try {
            $status = (int) $request->status;
            $saleReturn = SaleReturn::where('id', $request->return_id)->first();
            $sale = Sales::where('id', $saleReturn->sale_id)->first();
            $saleReturn->status = $status;
            $saleReturn->save();

            if ($status === '3') {
                FlashData::success_alert('Berhasil menolak retur penjualan');

                DB::commit();
                return redirect()->route('sale_returns.detail_sale_return', $saleReturn->id);
            }
            $this->saleReturnService->makeProcessTransactionReturn($saleReturn);
            $this->saleReturnService->cashFlowOutReturn($saleReturn);
            $this->saleReturnService->balanceCustomerSaleReturn($saleReturn);

            FlashData::success_alert('Berhasil menyetujui pengembalian pada transaksi ' . $sale->number);
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            DB::rollBack();
            return redirect()->back();
        }
    }
}
