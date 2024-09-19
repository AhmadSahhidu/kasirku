<?php

namespace App\Observers;

use App\Models\SubProduct;

class SubProductObserver
{
    public function creating(SubProduct $data)
    {
        $data->id = generateUuid();
    }
}
