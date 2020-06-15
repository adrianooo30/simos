<?php

namespace App\Dependency;

// custom class dependency
use App\Dependency\OrderDependency;
use App\Dependency\SalesDependency;

use App\Dependency\ProductDependency;
use App\Dependency\BatchNoDependency;

// model classes
use App\BatchNo;

class ReturneeDependency extends OrderDependency
{

	protected $productDependency;
	protected $batchNoDependency;

	protected $salesDependency;

	public function __construct()
	{
		//
	}

	public function set_returneeProduct()
	{
		//
	}

	public function get_returneeProducts()
	{
		$returnees = \App\Returnee::orderBy('id', 'desc')->get();

		foreach($returnees as $returnee)
		{
			// get each single returned batch number
			$this->get_returnedProductDetails($returnee);
		}

		$this->setPages($returnees);

		return $returnees;
	}

	// single returned batch number
	public function get_returnedProductDetails($returnee)
	{
		// get order transaction
		$orderTransaction = $returnee->orderBatchNo->orderMedicine->orderTransaction;
		// sales dependency
		$this->salesDependency = new SalesDependency();
		// set also the formatted details
		$this->salesDependency->get_salesDetails($orderTransaction);
		// set the ordertransactions total bill and excess payment
		$this->salesDependency->set_totalBill_and_excessPayment_Formatted($orderTransaction);
		$returnee['order_transaction'] = $orderTransaction;

		// product dependency
		$this->productDependency = new ProductDependency();
		// order medicine
		$returnee['product'] = $returnee->orderBatchNo->orderMedicine->product;
		// set well formed product details
		$this->productDependency->setProduct_Formatted($returnee['product']);
		// order batch number
		$returnee['batch_no'] = $returnee->orderBatchNo->batchNo;
		// set status sentence
		$this->__set_statusSentence_Formatted($returnee);
		// set well formatted total cost
		$this->__set_totalReturnee_Formatted($returnee);


		if($returnee['status'] === 'replace'){
			foreach($returnee->changedBatchNo as $changedBatchNo)
				$changedBatchNo->batchNo;
		}

		//  return each single returned batch number
		return $returnee;
	}

	// status of REPLACE
	public  function set_changedBatchNumbers($returnee ,$replaced_batchNos)
	{
		// listing the batch numbers to used in replacement
        foreach($replaced_batchNos as $replaced_batchNo) // replaced_batchNo
        {
            // creating the replacement for batch number
            $returnee->changedBatchNo()->create([
                'batch_no_id'  => $replaced_batchNo['batch_id'],
                'quantity' => $replaced_batchNo['batch_quantity'],
            ]);
            // updating the quantity of batch number in storage
            BatchNo::find( $replaced_batchNo['batch_id'] )->update([
                'quantity' => $replaced_batchNo['batch_updated_qty']
            ]);
        }
	}

	// IF THEIR ARE NEW ADDED OR UPDATED BATCH NUMBER
	public function set_toBeReplaced_orderBatchNo($productId)
	{
		$this->batchNoDependency = new BatchNoDependency();

		foreach($this->salesDependency->get_sales( $this->salesDependency->getQuery() ) as $orderTransaction){
			foreach($orderTransaction->orderMedicine as $orderMedicine){
				foreach($orderMedicine->orderBatchNo as $orderBatchNo){
					foreach($orderBatchNo->returnee as $returnee)
					{
						switch($returnee['status'])
						{
							case 'replace':
								if( !$returnee->replace['enough_supply'] ) {
									$replaced_batchNos = $this->batchNoDependency->get_usedBatchNos($productId, $returnee['quantity']);
			       					if( count($replaced_batchNos) > 0 )
			       						$this->set_changedBatchNumbers($returnee, $replaced_batchNos);
								}
							break;
						}
					}
				}
			}
		}
		
	}

	public function __set_totalReturnee_Formatted($returnee)
	{
		$returnee['total_cost'] = $returnee['quantity'] * $returnee->orderBatchNo->orderMedicine->product['unit_price'];
		$returnee['total_cost_format'] = $this->currencyType.' '.number_format($returnee['total_cost'], 2);

		return $returnee;
	}

	// set status sentence formatted
	public function __set_statusSentence_Formatted($returnee)
	{

		$account = $returnee->orderBatchNo->orderMedicine->orderTransaction->account;

		switch ($returnee['status']) {
            // REPLACE
            case 'replace':
                $returnee->replace;
                $returnee['status_sentence'] = $account['account_name'].' wants to <b class="text-warning">replace</b> the returned product.';
            break;

            // DONT REPLACE
            case 'dont replace' : 
                $returnee['status_sentence'] = $account['account_name'].' <b class="text-warning">don\'t want to replace</b> the returned product.';
            break;

            // RETURN PAYMENT
            case 'return payment' :
                $returnee->returnPayment;
                $returnee['status_sentence'] = $account['account_name'].' wants to <b class="text-warning">return the payment</b> for returned batch number - '.$returnee->orderBatchNo->batchNo['batch_no'].'.';
            break;
        }

        // return returnee including the status sentence
        return $returnee;
	}

	// **************************************************************
	//				ORDER TRANSACTIONS - RETURNEE DETAILS
	// **************************************************************
	public function get_salesReturneeDetails($orderTransaction)
	{
		foreach($orderTransaction->orderMedicine as $orderMedicine)
		{
			foreach($orderMedicine->orderBatchNo as $orderBatchNo)
			{
				$changedBatchNos = array();
				foreach($orderBatchNo->returnee as $returnee)
				{
					switch($returnee['status'])
					{
						case 'replace':
							if($returnee->replace['enough_supply'])
							{
								// loop through
								foreach($returnee->changedBatchNo as $changedBatchNo)
								{
									$changedBatchNo->batchNo;
									// then insert changed batch number
									array_push($changedBatchNos, $changedBatchNo);
								}
							}
						break;
					}
				}
				$orderBatchNo['changed_batch_no'] = $changedBatchNos;
			}
		}

		// return including returnee details
		return $orderTransaction;
	}

	
	// set order batch numbers quantity - access by order dependency
	public function set_orderBatchNo_Returnee($orderBatchNo)
	{
		$orderBatchNo['latest_quantity'] = $orderBatchNo['quantity'];
		// loop through each returnee
		foreach($orderBatchNo->returnee as $returnee)
		{
			switch($returnee['status'])
			{
				case 'replace':
					$orderBatchNo['latest_quantity'] += $returnee['quantity'];
				break;

				case 'dont replace':
					//
				break;

				case 'return payment':
					//
				break;
			}
		}

		// return orderBatchNo - with added quantity of replace product
		return $orderBatchNo;
	}

	
}