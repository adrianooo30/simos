<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\BatchNoDependency;
use App\Dependency\EmployeeDependency;

use App\Product;

// facades
use Illuminate\Support\Facades\Cache;

class ProductDependency extends BroadDependency
{
	
	protected $batchNoDependency;

	protected $employeeDependency;

	// initialize in each methods
	protected $soonExpiringDependency;

	public function __construct()
	{
		$this->batchNoDependency = new BatchNoDependency();
	}

	public function setProduct()
	{
		//
	}

	// all products
	public function getProducts()
	{
		$products = Product::orderBy('generic_name', 'asc')->get();
		// adding of attributes
		foreach($products as $product) {
            // adding attributes
            $this->getProductDetails($product);
        }
        // sets the pages
        $this->setPages($products);
        //
        return $products;
	}

	// each single product
	public function getProductDetails($product)
	{
		// initialized here to not cause  - fatal error
		$this->employeeDependency = new EmployeeDependency();

		// $this->get_currentHolder( $product );

		// set format
		$this->setProduct_Formatted($product);
		// get batch numbers		
		$this->batchNoDependency->getBatchNos($product->batchNo);
		// return product info
		return $product;
	}

	// GET CURRENT HOLDER
	public function get_currentHolder($product)
	{
		// holder
		$product['holder'] = null;
		foreach($product->employee as $employee) {
			// current holder of product... so on
			if( $employee->pivot['active'] ) {
				// get employee details
				$this->employeeDependency->getEmployeeDetails($employee);
				// set the employee in holder
				$product['holder'] = $employee;
			}
		}

		// return with holder
		return $product;
	}

	// method for setting formatted quantities and total cost
	public function setProduct_Formatted($product)
	{
		$product['stock'] = 0;
		$product['total_cost'] = 0;

		foreach($product->batchNo as $batchNo) {
            $product['stock'] += $batchNo['quantity'];
            $product['total_cost'] += $batchNo['quantity'] * $product['unit_price'];
		}

		// product formating details
		$this->get_productName_Formatted( $product );
		$this->get_unitPrice_Formatted( $product );
        
        // product formatting totals
		$product['stock_format'] = number_format($product['stock']).' pcs.';
		$product['total_cost_format'] = $this->currencyType.' '.number_format($product['total_cost'], 2);

		// return formatted details
		return $product;
	}

	// mini function
	public function get_productName_Formatted( $product ) {
        $product['generic_name&strength'] = $product['generic_name'].' '.$product['strength'];
		// return product name
		return $product;
	}

	public function get_unitPrice_Formatted( $product ) {
		$product['unit_price_format'] = $this->currencyType.' '.number_format($product['unit_price'], 2);
		// return unit price format
		return $product;
	}

	// *************************************************************************************
	// 				THIRD PARTY TRANSACTIONS -
	// *************************************************************************************

	// when in order - this is the stock number
	public function getProduct_stockInOrdering()
	{
		$products = array();
		// loop through all product
		foreach($this->getProducts() as $product) {
			// set well formatted data
			
			// if currently holding by logged in employee
			if( $this->employeeDependency->isCurrentlyHolding( $product ) ) {
				// set formatted
				$this->__set_forOrderProducts_Formatted( $product, $this->__get_totalStock_inOrdering($product) );
				// insert to array
				array_push( $products, $product );
			}
		}

		return $products;
	}

	// set well formatted stocks
	public function __set_forOrderProducts_Formatted($product, $totalQuantity)
	{
		// stock
		$product['stock']  = $totalQuantity;
		$product['stock_format'] = number_format($product['stock']);

		// total cost
		$product['total_cost'] = $totalQuantity * $product['unit_price'];
		$product['total_cost_format'] = $this->currencyType.' '.number_format( $product['total_cost'], 2 );

		return $product;
	}

	// total quantity of product - for ordering
	public function __get_totalStock_inOrdering($product)
	{
		$totalQuantity = 0;
		foreach($product->batchNo as $batchNo)
			$totalQuantity += $this->batchNoDependency->get_batchNoAbleToOrder($batchNo);
		return $totalQuantity;
	}

	// **************************************************
	//				THIRD PARTY CONNECTIONS
	// **************************************************

	// public function sampleMethod()
	// {
	// 	//
	// }
	

	// **************************************************
	//				SECTIONS FOR NOTIFICATIONS
	// **************************************************

	// TYPE OF NOTIFICATIONS
	
	// out of stock, critical

	// ALREADY DECLARED NOTIFICATION

	// soon expiring, expired

	// LOGIC FOR TRACKING OF EACH NOTIFICATION
	public function trackIf($typeOfNotification)
	{
		switch( $typeOfNotification )
		{
			case 'out of stock':
				// STOCK OF PRODUCTS IF IN ORDERING
				$outOfStock_Products = array();
				foreach($this->getProduct_stockInOrdering() as $product){
					if($product['stock'] === 0)
						array_push($outOfStock_Products, $product);
				}
				// if empty no recording of notifications
				if( count($outOfStock_Products) > 0 )
					$this->__createNotifications($typeOfNotification, $outOfStock_Products);
			break;

			case 'critical':
				$criticalStock_Products = array();

				foreach($this->getProduct_stockInOrdering() as $product){
					if($product['stock'] <= $product['critical_quantity'] && $product['stock'] > 0)
						array_push($criticalStock_Products, $product);
				}
				// if empty no recording of notifications
				if( count($criticalStock_Products) > 0 )
					$this->__createNotifications($typeOfNotification, $criticalStock_Products);

			break;

			case 'soon expiring':
				// will cause an error if declared in constructor
				$this->soonExpiringDependency = new SoonExpiringDependency();

				$soonExpiring_Products = $this->soonExpiringDependency->get_soonExpiringProducts();

				if( count($soonExpiring_Products) > 0 )
					$this->__createNotifications($typeOfNotification, $soonExpiring_Products);
			break;

			case 'expired':
				//
			break;
		}
	}

	// first param [ 'out of stock', 'critical', 'soon expiring', 'expired' ]
	// second param outOfStock_Products, criticalStock_Products, etc.

	public function __createNotifications($typeOfNotification, $dataAbles)
	{
		// notify every employee
		foreach(\App\Employee::all() as $employee)
		{
			switch($typeOfNotification)
			{
				case 'out of stock':
					foreach($dataAbles as $dataAble) {
						if($this->__doesntHave_inDatabase($employee, $dataAble))
							$employee->notify( new \App\Notifications\Product\OutOfStock( [ $dataAble ] ) );
					}
				break;

				case 'critical':
					foreach($dataAbles as $dataAble) {
						if($this->__doesntHave_inDatabase($employee, $dataAble))
							$employee->notify( new \App\Notifications\Product\Critical( [ $dataAble ] ) );
					}
				break;

				case 'soon expiring':
					//
				break;

				case 'expired':
					//
				break;
			}
		}
	}

	// check if data is already have in database
	public function __doesntHave_inDatabase($employee, $dataAble)
	{
		$doesntHave_inDatabase = true;

		foreach($employee->notifications as $notification) {
			if( json_encode( $notification['data'] ) == json_encode( [ $dataAble ] ) ) {
				$doesntHave_inDatabase = false;
			}
		}

		return $doesntHave_inDatabase;
	}



	// ***********************************************************************
	//						FOR HOLDING PURPOSE OF EMPLOYEE
	// ***********************************************************************

}