<?php

namespace App\Observers;

use App\Models\BalanceCustomer;

class BalanceCustomerObserver
{
    public function creating(BalanceCustomer $balanceCustomer)
    {
        $balanceCustomer->id = generateUuid();
    }
}
