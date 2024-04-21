<?php

namespace App\Observers;

use App\Models\SaleItems;

class SaleItemObserver
{
    public function creating(SaleItems $saleItems)
    {
        $saleItems->id = generateUuid();
    }
}
