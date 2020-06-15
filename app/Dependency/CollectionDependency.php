<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\OrderDependency;
use App\Dependency\SalesDependency;

use App\Dependency\EmployeeDependency;

// model classes
use App\CollectionTransaction;

class CollectionDependency extends BroadDependency
{
	protected $orderDependency;

    protected $salesDependency;

    protected $employeeDependency;

	public function __construct()
	{
        $this->orderDependency = new OrderDependency();
        $this->salesDependency = new SalesDependency();

		$this->employeeDependency = new EmployeeDependency();
	}

    public function setDeposit($collectionTransaction)
    {
        //
    }

    // public function get_collectionDatatable()
    // {
    //     $collectionTransactions = CollectionTransaction::orderBy('id', 'desc')->get();

    //     foreach ($collectionTransactions as $collectionTransaction)
    //     {
    //         $this->get_collectionDetails($collectionTransaction);
    //     }
        
    //     $this->setPages($collectionTransactions);

    //     return $collectionTransactions;
    // }

    public function get_collections()
    {
        $collectionTransactions = CollectionTransaction::orderBy('id', 'desc')->get();

        foreach ($collectionTransactions as $collectionTransaction)
        {
            $this->get_collectionDetails($collectionTransaction);
        }
        
        $this->setPages($collectionTransactions);

        return $collectionTransactions;
    }

    public function get_collectionDetails($collectionTransaction)
    {
        // get account
        $collectionTransaction['account'] = $collectionTransaction->orderMedicines->first()->orderTransaction->first()->account;
        // get employee - handled by
        $this->employeeDependency->setEmployee_Formatted($collectionTransaction->employee);

         // return null if not a cheque, return an array of cheque is not a cash
        $collectionTransaction->cheque;

        // set formatted amounts
        $this->__set_collectionFormatted($collectionTransaction);

        // deposit details for collection transaction
        if( !empty( $collectionTransaction->deposit ) )
            $this->employeeDependency->getEmployeeDetails($collectionTransaction->deposit->employee);

        // get paid bills
        $this->get_paidBillsFor($collectionTransaction);

        return $collectionTransaction;
    }

    // PAID BILLS COLLECTION TRANSACTION
    // public function get_paidBillsFor($collectionTransaction)
    // {
    //     // GET ALL ORDER TRANSACTION
    //     foreach($collectionTransaction->orderTransaction as $orderTransaction)
    //     {
    //         // GET TOTAL COST
    //         $this->salesDependency->set_orderTransaction_Formatted($orderTransaction);
    //         // GET RECEIPT NO
    //         $orderTransaction->deliverTransaction;
    //         // SETTING FORMAT
    //         $this->salesDependency->set_totalBill_and_excessPayment_Formatted($orderTransaction);
    //         $orderTransaction['paid_amount_format'] = $this->currencyType.' '.number_format($orderTransaction->pivot['paid_amount'], 2);
    //     }

    //     // COLLECTION TRANSACTION WITH
    //     return $collectionTransaction->orderTransaction;
    // }

    public function __set_collectionFormatted($collectionTransaction)
    {
        // GET TOTAL AMOUNT COLLECTED
        $collectionTransaction['collected_amount'] = $this->get_totalCollectedAmount($collectionTransaction);
        $collectionTransaction['collected_amount_format'] = $this->currencyType.' '.number_format($collectionTransaction['collected_amount'], 2);

        return $collectionTransaction;
    }

     // returns integer
    public function get_totalCollectedAmount($collectionTransaction)
    {
        $totalCollectedAmount = 0;
        // looping to all order transaction
        foreach($collectionTransaction->orderTransaction as $orderTransaction)
            $totalCollectedAmount += $orderTransaction->pivot['paid_amount'];
        // return total collected amount
        return $totalCollectedAmount;
    }


    // 
    public function get_accountInCollection( $collectionTransaction )
    {
        return $collectionTransaction->orderTransaction->first()->account;
    }
}