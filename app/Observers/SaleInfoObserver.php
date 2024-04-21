<?php

namespace App\Observers;

use App\Models\SaleInfo;

class SaleInfoObserver
{
    public function creating(SaleInfo $saleInfo)
    {
        $saleInfo->id = generateUuid();
    }
}
