<?php

namespace App\Observers;

use App\Models\PurchaseOrderCart;

class PurchaseOrderCartObserver
{
    public function creating(PurchaseOrderCart $data)
    {
        $data->id = generateUuid();
    }
}
