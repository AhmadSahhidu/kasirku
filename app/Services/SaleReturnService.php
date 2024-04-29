<?php

namespace App\Services;

use App\Models\BalanceCustomer;
use App\Models\CashFlow;
use App\Models\HistoryBalanceCustomer;
use App\Models\SaleItems;
use App\Models\SaleReturn;
use App\Models\Sales;

class SaleReturnService
{
    public function makeProcessTransactionReturn(SaleReturn $saleReturn): void
    {
        foreach ($saleReturn->returnItems as $items) {
            SaleItems::where('id', $items->sale_item_id)->update([
                'qty' => $items->items->qty - $items->qty
            ]);
        }

        $newTotal = $saleReturn->returnItems->sum('total');
        Sales::where('id', $saleReturn->sale_id)->update(['total' => $saleReturn->sales->total - $newTotal, 'grand_total' => $saleReturn->sales->grand_total - $newTotal]);
    }

    public function cashFlowOutReturn(SaleReturn $saleReturn): void
    {
        if ($saleReturn->sales->payment_method !== 3) {
            CashFlow::create([
                'number' => 'CSF-' . date('YmdHis'),
                'type_cash' => 2,
                'type_cash_out' => null,
                'amount' => $saleReturn->total,
                'note' => 'Pengembalian pada transaksi ' . $saleReturn->sales->number,
                'store_id' => $saleReturn->store_id
            ]);
        }
    }

    public function balanceCustomerSaleReturn(SaleReturn $saleReturn): void
    {
        if ($saleReturn->sales->payment_method !== 3) {
            $balance = BalanceCustomer::where('customer_id', $saleReturn->sales->customer_id)->first();
            $saldoAwal = $balance->nominal;
            $saldoAkhir = $balance->nominal + $saleReturn->total;

            BalanceCustomer::where('id', $balance->id)->update(['nominal' => $saldoAkhir]);
            HistoryBalanceCustomer::create([
                'number' => 'HBC-' . date('YmdHis'),
                'status' => 1,
                'note' => 'Pengembalian saldo pada pembayaran transaksi ' . $saleReturn->sales->number,
                'nominal' => $saleReturn->total,
                'start_balance' => $saldoAwal,
                'end_balance' => $saldoAkhir,
                'balance_id' => $balance->id,
            ]);
        }
    }
}
