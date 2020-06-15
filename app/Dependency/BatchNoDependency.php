<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\SupplierDependency;

use App\BatchNo;

class BatchNoDependency extends BroadDependency
{

	protected $supplierDependency;

	public function __construct()
	{
		$this->supplierDependency = new SupplierDependency();
	}

	public function setBatchNo()
	{
		//
	}

	// all batch numbers from each single product
	public function getBatchNos($batchNos)
	{
		// adding computed attributes
		foreach($batchNos as $batchNo)
		{
			// every single batch number
			$this->getBatchNoDetails($batchNo);
		}
		// set pages
		$this->setPages($batchNos);
		// return batch number
		return $batchNos;
	}

	// each single batch number
	public function getBatchNoDetails($batchNo)
	{
		// get supplier
		$this->supplierDependency->getSupplierDetails($batchNo->supplier);
		// 
		$this->setBatchNo_Formatted($batchNo);
		//
		return $batchNo;
	}


	// method for setting formatted quantities and total cost
	public function setBatchNo_Formatted($batchNo)
	{
		$batchNo['quantity_format'] = number_format($batchNo['quantity']).' pcs.';
		// i dont use this in product management
		$batchNo['total_cost'] = $batchNo['quantity'] * $batchNo->product['unit_price'];
		$batchNo['total_cost_format'] = $this->currencyType.' '.number_format($batchNo['total_cost'], 2);
		// get the unit cost
		$batchNo['unit_cost_format'] = $this->currencyType.' '.number_format($batchNo['unit_cost'], 2);
		// return batch number formatted
		return $batchNo;
	}

	// *********************************************************************
	//					THIRD PARTY CONNECTION - HEHEHE
	// *********************************************************************

	// exclude the expired batch number - return 0 quantity
	public function get_batchNoAbleToOrder($batchNo)
	{
		if(date('Y-m-d') < $batchNo['exp_date'])
			return $batchNo['quantity'];

		// return 0 if the batch number is expired
		return 0;
	}

    // BATCH NOS THAT ARE GOING TO USE
    public function get_usedBatchNos($product, $quantity)
    {
        $used_batchNos = array();

        /*batch numbers that will use to complete the quantity of product, for ordering*/
        $batch_nosToUse = BatchNo::where('product_id', $product)
	                        ->where('quantity', '>', 0)
	                        ->where('exp_date', '>', date('Y-m-d'))
	                        ->orderBy('exp_date', 'asc')
	                        ->get();

        if( count($batch_nosToUse) > 0 )
        {
        	$combined_batch_qty = $batch_nosToUse[0]['quantity'];

        	// return null, if the requested quantity for the product is greater than the stock
        	if($batch_nosToUse->count() == 1 && $quantity > $combined_batch_qty)
        		return [];

	        for($i = 0; $i < count($batch_nosToUse); $i++){
	        	// list of inadequate
	            $inadequate = $combined_batch_qty - $quantity;

	            if($inadequate < 0){
	                $combined_batch_qty += $batch_nosToUse[$i+1]['quantity'];

	                $used_batchNos[$i]['batch_id'] = $batch_nosToUse[$i]['id'];
	                $used_batchNos[$i]['batch_updated_qty'] = 0;
	                $used_batchNos[$i]['batch_quantity'] = $batch_nosToUse[$i]['quantity'];
	            }

	            else{
	                $used_batchNos[$i]['batch_id'] = $batch_nosToUse[$i]['id'];
	                $used_batchNos[$i]['batch_updated_qty'] = $inadequate;
	                $used_batchNos[$i]['batch_quantity'] = $batch_nosToUse[$i]['quantity'] - $inadequate;
	                break;
	            }

	        }
	        /*end of batch numbers that will use to complete the quantity of product, for ordering*/
        }

        return $used_batchNos;
    }
}