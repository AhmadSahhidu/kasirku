<?php

namespace App\Observers;

use App\Models\FlowDetbPaymentHistory;

class FlowDetbPaymentHistoryObserver
{
    public function creating(FlowDetbPaymentHistory $data)
    {
        $data->id = generateUuid();
    }
}
