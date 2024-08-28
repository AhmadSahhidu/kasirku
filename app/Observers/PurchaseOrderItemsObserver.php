<?php

namespace App\Observers;

use App\Models\PurchaseOrderItems;

class PurchaseOrderItemsObserver
{
    public function creating(PurchaseOrderItems $data)
    {
        $data->id = generateUuid();
    }
}
