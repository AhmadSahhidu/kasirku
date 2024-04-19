<?php

namespace App\Observers;

use App\Models\Store;

class StoreObserver
{
    public function creating(Store $Store)
    {
        $Store->id = generateUuid();
    }

    /**
     * Handle the Store "created" event.
     *
     * @return void
     */
    public function created(Store $Store)
    {
        //
    }

    /**
     * Handle the Store "updated" event.
     *
     * @return void
     */
    public function updated(Store $Store)
    {
        //
    }

    /**
     * Handle the Store "deleted" event.
     *
     * @return void
     */
    public function deleted(Store $Store)
    {
        //
    }

    /**
     * Handle the Store "restored" event.
     *
     * @return void
     */
    public function restored(Store $Store)
    {
        //
    }

    /**
     * Handle the Store "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Store $Store)
    {
        //
    }
}
