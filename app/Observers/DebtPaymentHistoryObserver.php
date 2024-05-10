<?php

namespace App\Observers;

use App\Models\DebtPaymentHistory;

class DebtPaymentHistoryObserver
{
    public function creating(DebtPaymentHistory $debtPaymentHistory)
    {
        $debtPaymentHistory->id = generateUuid();
    }
}
