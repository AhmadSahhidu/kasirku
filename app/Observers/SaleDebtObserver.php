<?php

namespace App\Observers;

use App\Models\SaleDebtPayment;

class SaleDebtObserver
{
    public function creating(SaleDebtPayment $saleDebtPayment)
    {
        $saleDebtPayment->id = generateUuid();
    }
}
