<?php

namespace App\Observers;

use App\BatchNo;

// notification generator
use App\CustomClasses\NotificationGenerator;

class BatchNoObserver
{
    /**
     * Handle the batch no "created" event.
     *
     * @param  \App\BatchNo  $batchNo
     * @return void
     */
    public function created(BatchNo $batchNo)
    {
        //
    }

    /**
     * Handle the batch no "updated" event.
     *
     * @param  \App\BatchNo  $batchNo
     * @return void
     */
    public function updated(BatchNo $batchNo)
    {
        NotificationGenerator::criticalStock($batchNo->product);
        NotificationGenerator::outOfStock($batchNo->product); 
    }

    /**
     * Handle the batch no "deleted" event.
     *
     * @param  \App\BatchNo  $batchNo
     * @return void
     */
    public function deleted(BatchNo $batchNo)
    {
        //
    }

    /**
     * Handle the batch no "restored" event.
     *
     * @param  \App\BatchNo  $batchNo
     * @return void
     */
    public function restored(BatchNo $batchNo)
    {
        //
    }

    /**
     * Handle the batch no "force deleted" event.
     *
     * @param  \App\BatchNo  $batchNo
     * @return void
     */
    public function forceDeleted(BatchNo $batchNo)
    {
        //
    }
}
