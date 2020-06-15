<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// event
use App\Events\HasNewReturnedMedicine;


// only for single ordered medicine in a single order transaction
class IsLessenPaidQuantityListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $orderMedicine = $event->orderMedicine
                ->withQuantityWOFree()
                ->withPaid()
                ->find($event->orderMedicine['id']);

        // LESSEN THE PAID QUANTITY...
        $excess_paid_quantity_global = 0;
        $excess_paid_quantity = 0;
        if($orderMedicine['total_paid_quantity'] > $orderMedicine['quantity_wo_free'])
        {
            $excess_paid_quantity = $orderMedicine['total_paid_quantity'] - $orderMedicine['quantity_wo_free'];
            // to access in global...
            $excess_paid_quantity_global = $orderMedicine['total_paid_quantity'] - $orderMedicine['quantity_wo_free'];

            // get collections
            $collectionTransactions = $orderMedicine
                                    ->collectionTransactions()
                                    ->orderBy('id', 'desc')
                                    ->get();

            // loop through collections...
            foreach($collectionTransactions as $collection){
                // kung kulang ang naunang collection transactions - paid quantity
                if( $excess_paid_quantity > $collection['paid']['paid_quantity'] )
                {
                    // lessen the excess paid quantity, since it update the paid quantity to 0
                    $excess_paid_quantity -= $collection['paid']['paid_quantity'];
                    // update to 0
                    $orderMedicine->collectionTransactions()
                                ->find( $collection['id'] )
                                ->paid
                                ->update([
                                    'paid_quantity' => 0,
                                    'paid_amount' => 0,
                                ]);
                }

                // kung okay na yung current collection paid quantity - para sa sobrang bawas
                else{
                    // get the paid - pivot table
                    $paid = $orderMedicine->collectionTransactions()
                                ->find( $collection['id'] )
                                ->paid;
                    // update the paid quantity
                    $paid
                        ->update([
                            'paid_quantity' => $paid['paid_quantity'] - $excess_paid_quantity,
                            'paid_amount' => ($paid['paid_quantity'] - $excess_paid_quantity) * $orderMedicine['unit_price'],
                        ]);
                }
            }
        }

        // set excess payment
        $this->setExcessPayment( $orderMedicine, $excess_paid_quantity_global );
    }

    // set excess payment for the account
    public function setExcessPayment($orderMedicine, $excess_payment)
    {
        // SET THE EXCESS PAYMENT OF THE ACCOUNT... 
        $account = $orderMedicine
                        ->orderTransaction
                        ->account;

        // set total bill
        $account->setTotalBill();
        // set excess payment
        $account
            ->accountMisc
            ->update([
                        'excess_payment' => $account->accountMisc['excess_payment'] + 
                                            ($excess_payment * $orderMedicine['unit_price']),
                    ]);
    }
}
