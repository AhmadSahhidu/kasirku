<?php

namespace App\Observers;

use App\Models\StockOpname;

class StockOpnameObserver
{
    public function creating(StockOpname $stockOpname)
    {
        $stockOpname->id = generateUuid();
    }
}
