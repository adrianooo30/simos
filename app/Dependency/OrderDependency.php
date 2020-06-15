<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\EmployeeDependency;
use App\Dependency\ReturneeDependency;
use App\Dependency\ProductDependency;

// for plugin
use Yajra\Datatables\DataTables;

class OrderDependency extends BroadDependency
{

	protected $returneeDependency;
	protected $productDependency;

	public function __construct()
	{
		//
	}

	//*********************************************************************************
	// 							ORDER TRANSACTIONS
	//*********************************************************************************
	public function getOrderTransactions($orderTransactions) // parameter receives the query
	{
		// get ORDER TRANSACTIONS
		foreach($orderTransactions as $orderTransaction)
		{
			$this->get_orderTransactionDetails($orderTransaction);
		}
		// set PAGES
		$this->setPages($orderTransactions);
		// return order transaction
		return $orderTransactions;
	}

	//***********************************************************************************
	// 							SINGLE ORDER TRANSACTION
	//***********************************************************************************
	public function get_orderTransactionDetails($orderTransaction)
	{
		//  initialized here... coz construct is not called
		// $this->employeeDependency = new EmployeeDependency();

		// get ACCOUNT
		$orderTransaction->account;
		// get EMPLOYEE
		$orderTransaction->employee;
		// $this->employeeDependency->setEmployee_Formatted($orderTransaction->employee);
		// unified text
		$this->set_orderTransaction_Formatted($orderTransaction);
		// get ORDER MEDICINES and its ORDER BATCH NO
		$this->get_orderMedicines($orderTransaction->orderMedicine);
		
		// return order transaction
		return $orderTransaction;
	}
	// formatting
	public function set_orderTransaction_Formatted($orderTransaction)
	{
		// getting order transaction total cost
		$orderTransaction['total_cost'] = $this->get_orderTransaction_Total($orderTransaction)['cost'];
		// setting the format
		$orderTransaction['total_cost_format'] = $this->currencyType.' '.number_format($orderTransaction['total_cost'], 2);
		// return order transaction
		return $orderTransaction;
	}

	//*************************************************************************************
	//								ORDER MEDICINES
	//*************************************************************************************
	public function get_orderMedicines($orderMedicines)
	{
		//  initialized here... coz construct is not called
		$this->productDependency = new ProductDependency();

		foreach($orderMedicines as $orderMedicine)
		{
			// get PRODUCT DETAILS
			$this->productDependency->getProductDetails($orderMedicine->product);
			// set order medicine formatted
			$this->set_orderMedicine_Formatted($orderMedicine);
			// get ORDER BATCH NUMBERS
			$this->getOrderBatchNos($orderMedicine->orderBatchNo);
		}
		return $orderMedicines;
	}
	// formatting
	public function set_orderMedicine_Formatted($orderMedicine)
	{					
		$orderMedicine['quantity'] = $this->get_orderMedicine_Total($orderMedicine)['quantity'];
		$orderMedicine['total_cost'] = $this->get_orderMedicine_Total($orderMedicine)['cost'];

		// unified text
		$orderMedicine['quantity_format'] = number_format($orderMedicine['quantity']).' pcs.';
		$orderMedicine['total_cost_format'] = $this->currencyType.' '.number_format($orderMedicine['total_cost'], 2);

		return $orderMedicine;
	}


	//************************************************************************************
	//								ORDER BATCH NUMBERS
	//************************************************************************************
	
	public function getOrderBatchNos($orderBatchNos)
	{
		foreach($orderBatchNos as $orderBatchNo)
		{
			// get BATCH NUMBER DETAILS
			$orderBatchNo->batchNo;
			$orderBatchNo->batchNo->supplier;

			$this->__set_orderBatchNo_Formatted($orderBatchNo);
		}

		return $orderBatchNos;
	}

	// formatting
	public function __set_orderBatchNo_Formatted($orderBatchNo)
	{
		// unified text
		$orderBatchNo['total_cost'] = $this->get_orderBatchNo_Total($orderBatchNo)['cost'];
		// unified text
		$orderBatchNo['quantity_format'] = number_format($this->get_orderBatchNo_Total($orderBatchNo)['quantity']).' pcs.';
		$orderBatchNo['total_cost_format'] = number_format($orderBatchNo['total_cost'], 2);
		// return order batch number
		return $orderBatchNo;
	}




	// *************************************************
	// 			ORDER TRANSACTION TOTALS
	// *************************************************
	public function get_orderTransaction_Total($orderTransaction)
	{
		$attribute = [ 'cost' => 0 ];
		foreach($orderTransaction->orderMedicine as $orderMedicine)
			$attribute['cost'] += $this->get_orderMedicine_Total($orderMedicine)['cost'];
		return $attribute;
	}
	// *****************************************************
	// 			ORDER MEDICINE TOTALS
	// *****************************************************
	public function get_orderMedicine_Total($orderMedicine)
	{
		$attribute = [ 'quantity' => 0, 'free' => 0, 'cost' => 0];
		// order batch number
		foreach($orderMedicine->orderBatchNo as $orderBatchNo) {
			$attribute['quantity'] += $this->get_orderBatchNo_Total($orderBatchNo)['quantity'] - $orderMedicine['free'];
			$attribute['cost'] += $this->get_orderBatchNo_Total($orderBatchNo)['cost'] - $this->__get_freePrice($orderMedicine);
		}
		// return attribute
		return $attribute;
	}

	public function __get_freePrice($orderMedicine)
	{
		return $orderMedicine['free'] * $orderMedicine['unit_price'];
	}


	// ****************************************************************
	// 			ORDER BATCH NUMBER TOTALS - THIRD PARTY OF RETURNEE
	// ****************************************************************
	public function get_orderBatchNo_Total($orderBatchNo)
	{
		// ORIGINAL QUANTITY
		$attribute['quantity'] = $orderBatchNo['quantity'];
		// LATEST QUANTITY
		$attribute['cost'] = $orderBatchNo['quantity'] * $orderBatchNo->orderMedicine['unit_price'];

		return $attribute;
	}


	// can be a second party relationship hahahahahaha

	// DATATABLES SHOW ORDERED MEDICINE
	public function showOrderMedicine($orderTransaction)
	{
		return DataTables::of( $orderTransaction->orderMedicine() )
                // add columns
                ->addColumn('product_img', function($orderMedicine){
                	return '<div align="center">
                                <img src="'.$orderMedicine['product']['product_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->addColumn('product_name', function($orderMedicine){
                	return '<div>
                                <h5 class="text-darker">'.$orderMedicine['product']['product_name'].'</h5>
                                <h6 class="text-muted">'.$orderMedicine['product']['brand_name'].'</h6>
                                <h6 class="text-muted">'.$orderMedicine->pesoFormat($orderMedicine['unit_price']).'</h6>
                            </div>';
                })
                ->addColumn('batch_nos', function($orderMedicine){

                	$batchNos = '';
                	foreach( $orderMedicine->orderBatchNo as $orderBatchNo ) {
						$batchNos .= '<span class="text-darker font-12">'.$orderBatchNo->batchNo['batch_no'].' - '.$orderBatchNo->quantityFormat($orderBatchNo['quantity']).'</span><br>';
                	}

                	return $batchNos;

                })
                ->addColumn('quantity_and_free', function( $orderMedicine ){
					return '<strong>'.$orderMedicine['total_quantity'].' + '.$orderMedicine['free'].' pcs.</strong>';
                })
                ->addColumn('action', function( $orderMedicine ){
                    return '
                        <div class="d-flex">
                            <button class="btn btn-sm btn-warning waves-effect wave-light mx-2" data-toggle="modal" data-target="#edit-order-batch-no-modal" onclick="details('.$orderMedicine['id'].')">
                                <i class="ti-write"></i>
                            </button>
                        </div>';
                })
                ->addColumn('total_cost_format', function($orderMedicine){
                	return '<span class="font-weight-bold text-primary">'.$orderMedicine->pesoFormat( $orderMedicine['total_cost'] ).'</span>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'batch_nos', 'quantity_and_free', 'total_cost_format', 'action',])
                //convert to json
                ->toJson();
	}
}