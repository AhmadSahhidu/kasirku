<?php

namespace App\Observers;

use App\Models\Sales;

class SaleObserver
{
    public function creating(Sales $sales)
    {
        $sales->id = generateUuid();
        $sales->number = Sales::generateNumber();
    }
}
