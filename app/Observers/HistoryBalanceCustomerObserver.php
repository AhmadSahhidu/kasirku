<?php

namespace App\Observers;

use App\Models\HistoryBalanceCustomer;

class HistoryBalanceCustomerObserver
{
    public function creating(HistoryBalanceCustomer $historyBalanceCustomer)
    {
        $historyBalanceCustomer->id = generateUuid();
    }
}
