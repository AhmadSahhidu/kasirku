<?php

namespace App\Observers;

use App\Models\SaleReturnItem;

class SaleReturnItemObserver
{
    public function creating(SaleReturnItem $sales)
    {
        $sales->id = generateUuid();
    }
}
