<?php

namespace App\Observers;

use App\Models\Cash;

class CashObserver
{
    public function creating(Cash $cash)
    {
        $cash->id = generateUuid();
    }
}
