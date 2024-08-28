<?php

namespace App\Observers;

use App\Models\PurchaseOrderPayment;

class PurchaseOrderPaymentObserver
{
    public function creating(PurchaseOrderPayment $data)
    {
        $data->id = generateUuid();
    }
}
