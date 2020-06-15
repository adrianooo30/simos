<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\AccountDependency;

use App\Dependency\OrderDependency;
use App\Dependency\SalesDependency;

// model classes
use App\Account;

class ReceivablesDependency extends BroadDependency
{

    protected $accountDependency;

	protected $orderDependency;

    protected $salesDependency;


	public function __construct( AccountDependency $accountDependency, OrderDependency $orderDependency, SalesDependency $salesDependency)
	{
        $this->orderDependency = $orderDependency;
		$this->salesDependency = $salesDependency;

        $this->accountDependency = $accountDependency;
	}

	public function getReceivables($accounts)
	{
        // return accounts with balance
		return $this->accountDependency->get_accountsWithBalance($accounts);
	}

    public function get_receivableDetails($account)
    {
        $account['order_transactions'];
        // storing the right order transction
        $tempOrderTransaction = array();
        // get all order transaction - that has to be paid
        foreach ($account->orderTransaction as $orderTransaction)
        {
            if($orderTransaction['status'] === 'Delivered' || $orderTransaction['status'] === 'Partially Paid')
                array_push($tempOrderTransaction, $this->orderDependency->get_orderTransactionDetails($orderTransaction));
        }
        // get total balance of account
        $this->accountDependency->get_accountTotalBalance($account);
        // i cannot get the right output so. i put it in custom_order_transaction
        $account['custom_order_transaction'] = $tempOrderTransaction;
        // returns account
        return $account;
    }

    // access via ajax
    // each single account bills
    public function get_tableOfBills($account)
    {
        $orderTransactions = array();

        foreach ($account->orderTransaction as $orderTransaction) {
            if($orderTransaction->status == 'Delivered' || $orderTransaction->status == 'Partially Paid')
            {
                $orderTransaction->deliverTransaction;
                // get the order transactions details
                $this->orderDependency->get_orderTransactionDetails($orderTransaction);
                // get well formatted
                $this->__set_tableOfBills_Formatted($orderTransaction);

                array_push($orderTransactions, $orderTransaction);
            }
        }

        $this->setPages($orderTransactions);

        return $orderTransactions;
    }

    // access only by this class
    public function __set_tableOfBills_Formatted($orderTransaction)
    {
        $orderTransaction['bill_amount'] = $this->salesDependency->getTotalBill($orderTransaction);
        $orderTransaction['bill_amount_format'] = $this->currencyType.' '.number_format($orderTransaction['bill_amount'], 2);

        $orderTransaction['paid_amount'] = $this->salesDependency->getTotalPaid($orderTransaction);
        $orderTransaction['paid_amount_format'] = $this->currencyType.' '.number_format($orderTransaction['paid_amount'], 2);

        return $orderTransaction;
    }
}