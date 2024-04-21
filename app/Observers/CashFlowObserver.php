<?php

namespace App\Observers;

use App\Models\CashFlow;

class CashFlowObserver
{
    public function creating(CashFlow $cashFlow)
    {
        $cashFlow->id = generateUuid();
    }
}
