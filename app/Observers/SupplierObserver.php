<?php

namespace App\Observers;

use App\Models\Supplier;

class SupplierObserver
{
    public function creating(Supplier $supplier)
    {
        $supplier->id = generateUuid();
    }
}
