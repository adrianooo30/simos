<?php

namespace App\Observers;

use App\OrderBatchNo;

class OrderBatchNoObserver
{
    /**
     * Handle the order batch no "created" event.
     *
     * @param  \App\OrderBatchNo  $orderBatchNo
     * @return void
     */
    public function created(OrderBatchNo $orderBatchNo)
    {
        
    }

    /**
     * Handle the order batch no "updated" event.
     *
     * @param  \App\OrderBatchNo  $orderBatchNo
     * @return void
     */
    public function updated(OrderBatchNo $orderBatchNo)
    {
        // return $orderBatchNo->orderMedicine()->orderTransaction;
    }

    /**
     * Handle the order batch no "deleted" event.
     *
     * @param  \App\OrderBatchNo  $orderBatchNo
     * @return void
     */
    public function deleted(OrderBatchNo $orderBatchNo)
    {
        //
    }

    /**
     * Handle the order batch no "restored" event.
     *
     * @param  \App\OrderBatchNo  $orderBatchNo
     * @return void
     */
    public function restored(OrderBatchNo $orderBatchNo)
    {
        //
    }

    /**
     * Handle the order batch no "force deleted" event.
     *
     * @param  \App\OrderBatchNo  $orderBatchNo
     * @return void
     */
    public function forceDeleted(OrderBatchNo $orderBatchNo)
    {
        //
    }
}
