<?php

namespace App\Dependency;

// custom class dependency
use App\Dependency\OrderDependency;

// model class
use App\OrderTransaction;

class ViewOrderDependency extends OrderDependency
{
	public function __construct()
	{
		//
	}

	public function getQuery()
	{
		return OrderTransaction::orderBy('id', 'desc')
                ->where('status', 'Pending')
                ->get();
	}

	public function get_viewOrders($orderTransactions)
	{	
		// view order details
		$this->getOrderTransactions($orderTransactions);
		// return orderTransactions
		return $orderTransactions;
	}

	public function get_viewOrderDetails($orderTransaction)
	{
		// get ORDER TRANSACTION - with its unified data
		$this->get_orderTransactionDetails($orderTransaction);
		// return order transaction
		return $orderTransaction;
	}
}