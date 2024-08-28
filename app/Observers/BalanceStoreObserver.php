<?php

namespace App\Observers;

use App\Models\BalanceStores;

class BalanceStoreObserver
{
    public function creating(BalanceStores $data)
    {
        $data->id = generateUuid();
    }
}
