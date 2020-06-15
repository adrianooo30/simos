<?php

namespace App\Observers;

use App\OrderTransaction;

use App\CustomClasses\NotificationGenerator;

class OrderTransactionObserver
{
    /**
     * Handle the order transaction "created" event.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return void
     */
    public function created(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Handle the order transaction "updated" event.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return void
     */
    public function updated(OrderTransaction $orderTransaction)
    {
        if($orderTransaction['status'] == 'Delivered')
            $orderTransaction->account->setTotalBill();

        // set total bill...
        // $orderTransaction
        //     ->account
        //     ->setTotalBill();

        // // updates on created order
        // NotificationGenerator::updatesOnCreatedOrder( $orderTransaction );
    }

    /**
     * Handle the order transaction "deleted" event.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return void
     */
    public function deleted(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Handle the order transaction "restored" event.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return void
     */
    public function restored(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Handle the order transaction "force deleted" event.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return void
     */
    public function forceDeleted(OrderTransaction $orderTransaction)
    {
        //
    }
}
