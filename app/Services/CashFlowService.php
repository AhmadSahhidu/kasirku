<?php

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Sales;
use Exception;

class CashFlowService
{
    /**
     * @throws Exception
     */
    public function cashFlowInCashier(Sales $sale): void
    {
        if ($sale->payment_method !== 3) {
            CashFlow::create([
                'number' => 'CSF-' . date('YmdHis'),
                'type_cash' => 1,
                'type_cash_out' => null,
                'amount' => $sale->grand_total,
                'note' => 'Penjualan pada transaksi ' . $sale->number,
                'store_id' => $sale->store_id
            ]);
        }
    }
}
