<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    public function creating(Brand $Brand)
    {
        $Brand->id = generateUuid();
    }

    /**
     * Handle the Brand "created" event.
     *
     * @return void
     */
    public function created(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @return void
     */
    public function updated(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @return void
     */
    public function deleted(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Brand "reBrandd" event.
     *
     * @return void
     */
    public function reBrandd(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Brand $Brand)
    {
        //
    }
}
