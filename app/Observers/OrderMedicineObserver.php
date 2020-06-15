<?php

namespace App\Observers;

use App\OrderMedicine;

class OrderMedicineObserver
{
    /**
     * Handle the order medicine "created" event.
     *
     * @param  \App\OrderMedicine  $orderMedicine
     * @return void
     */
    public function created(OrderMedicine $orderMedicine)
    {
        //
    }

    /**
     * Handle the order medicine "updated" event.
     *
     * @param  \App\OrderMedicine  $orderMedicine
     * @return void
     */
    public function updated(OrderMedicine $orderMedicine)
    {
        //
    }

    /**
     * Handle the order medicine "deleted" event.
     *
     * @param  \App\OrderMedicine  $orderMedicine
     * @return void
     */
    public function deleted(OrderMedicine $orderMedicine)
    {
        //
    }

    /**
     * Handle the order medicine "restored" event.
     *
     * @param  \App\OrderMedicine  $orderMedicine
     * @return void
     */
    public function restored(OrderMedicine $orderMedicine)
    {
        //
    }

    /**
     * Handle the order medicine "force deleted" event.
     *
     * @param  \App\OrderMedicine  $orderMedicine
     * @return void
     */
    public function forceDeleted(OrderMedicine $orderMedicine)
    {
        //
    }
}
