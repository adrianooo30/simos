<?php

namespace App\Dependency;

// custom class dependency
use App\Dependency\OrderDependency;

use App\Dependency\ReturneeDependency;
use App\Dependency\CollectionDependency;

// model class
use App\OrderTransaction;

class SalesDependency extends OrderDependency
{

	protected $returneeDependency;

	protected $collectionDependency;

	public function __construct()
	{
        $this->returneeDependency = new ReturneeDependency();
	}

	public function getQuery()
	{
		return OrderTransaction::orderBy('id', 'desc')
                ->where('status', 'Delivered')
                ->orWhere('status', 'Partially Paid')
                ->orWhere('status', 'Fully Paid')
                ->get();
	}

	public function get_sales($orderTransactions)
	{
		// view order details
		$this->getOrderTransactions($orderTransactions);
		// get each single deliver transaction
		foreach($orderTransactions as $orderTransaction)
			$orderTransaction->deliverTransaction->employee;

		// return orderTransactions
		return $orderTransactions;
	}

	public function get_salesDetails($orderTransaction)
	{

		$this->collectionDependency = new CollectionDependency();

		// get deliver transaction - and employee
		$orderTransaction->deliverTransaction->employee;
		// get total bill
		$this->set_totalBill_and_excessPayment_Formatted($orderTransaction);

		// set status html formatted
		// $this->__set_statusHTMLFormatted($orderTransaction);

		// get RETURNEES - including the batch number that is replace to returned products
		// $this->returneeDependency->get_salesReturneeDetails($orderTransaction);

		// get ORDER TRANSACTION - with its unified data
		$this->get_orderTransactionDetails($orderTransaction);
		
		// return order transaction
		return $orderTransaction;
	}

	// bill and excess payment
	public function set_totalBill_and_excessPayment_Formatted($orderTransaction)
	{
		$totalBill = $this->getTotalBill($orderTransaction);

		$excessPayment = 0;
		if($totalBill <= 0){
			$excessPayment = ($totalBill - $totalBill) - $totalBill;
			$totalBill = 0;
		}

		$orderTransaction['total_bill'] = $totalBill;
		$orderTransaction['total_bill_format'] = $this->currencyType.' '.number_format($totalBill, 2);

		$orderTransaction['excess_payment'] = $excessPayment;
		$orderTransaction['excess_payment_format'] = $this->currencyType.' '.number_format($excessPayment, 2);

		
		return $orderTransaction;
	}

	// well formatted status
	// public function __set_statusHTMLFormatted($orderTransaction)
	// {
	// 	switch($orderTransaction['status'])
 //        {
 //            case 'Delivered':
 //                $orderTransaction['status'] = '<button class="status-btn status-btn-danger" disabled>Not Paid</button>';
 //            break;
 //            case 'Partially Paid':
 //                $orderTransaction['status'] = '<button class="status-btn status-btn-warning" disabled>Partially Paid</button>';
 //            break;
 //            case 'Fully Paid':
 //                $orderTransaction['status'] = '<button class="status-btn status-btn-primary" disabled>Fully Paid</button>';
 //            break;
 //        }

 //        // return order transaction status in html format
 //        return $orderTransaction;
	// }

	// *********************************************************************************
	//					THIRD PARTY CONNECTION - for me hehehe
	// *********************************************************************************

	// total bill of order transaction
	public function getTotalBill($orderTransaction)
	{
		return $this->get_orderTransaction_Total($orderTransaction)['cost'] - $this->getTotalPaid($orderTransaction);
	}

	// total paid of single order transaction
	public function getTotalPaid($orderTransaction)
	{
		$totalPaid = 0;
		foreach($orderTransaction->collectionTransaction as $collectionTransaction) {
			$totalPaid += $collectionTransaction->pivot['paid_amount'];
		}
		// returns total paid
		return $totalPaid;
	}

	// ******************************************************************
	//			SECURING THE STATUS OF EACH ORDER TRANSACTION
	// ******************************************************************

	public function __if_notPaid($orderTransaction)
	{
		//
	}

	public function __if_partiallyPaid($orderTransaction)
	{
		//
	}

	public function __if_fullyPaid($orderTransaction)
	{
		//
	}

	
}