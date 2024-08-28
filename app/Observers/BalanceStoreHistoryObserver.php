<?php

namespace App\Observers;

use App\Models\BalanceStoreHistory;

class BalanceStoreHistoryObserver
{
    public function creating(BalanceStoreHistory $balance)
    {
        $balance->id = generateUuid();
    }
}
