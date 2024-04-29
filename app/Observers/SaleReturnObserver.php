<?php

namespace App\Observers;

use App\Models\SaleReturn;

class SaleReturnObserver
{
    public function creating(SaleReturn $sales)
    {
        $sales->id = generateUuid();
        $sales->number = SaleReturn::generateNumber();
    }
}
