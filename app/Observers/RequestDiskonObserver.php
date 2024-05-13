<?php

namespace App\Observers;

use App\Models\RequestDiskon;

class RequestDiskonObserver
{
    public function creating(RequestDiskon $diskon)
    {
        $diskon->id = generateUuid();
    }
}
