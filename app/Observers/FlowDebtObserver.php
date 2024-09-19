<?php

namespace App\Observers;

use App\Models\FlowDebt;

class FlowDebtObserver
{
    public function creating(FlowDebt $data)
    {
        $data->id = generateUuid();
    }
}
