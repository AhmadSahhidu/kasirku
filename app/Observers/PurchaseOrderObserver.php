<?php

namespace App\Observers;

use App\Models\PurchaseOrder;

class PurchaseOrderObserver
{
    public function creating(PurchaseOrder $data)
    {
        $data->id = generateUuid();
    }
}
