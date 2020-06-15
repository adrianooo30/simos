<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\ProductDependency;
use App\Dependency\BatchNoDependency;

// packages
use Carbon\Carbon;

// model class
use App\Product;

class SoonExpiringDependency extends BroadDependency
{

	protected $productDependency;

    protected $batchNoDependency;

	public function __construct()
	{
        $this->productDependency = new ProductDependency();
		$this->batchNoDependency = new BatchNoDependency();
	}

	public function get_soonExpiringProducts()
	{
        $soonExpiringProducts = array();

        foreach(Product::all() as $product)
        {
            foreach($product->batchNo as $batchNo)
            {
                // get product details
                $this->productDependency->getProductDetails($batchNo->product);

                // set batch number well formatted
                $this->batchNoDependency->setBatchNo_Formatted($batchNo);

                $currentDate = new Carbon();

                $currentDate->addDays(-1); // subtracts the day

                $soonExpireWithin = [
                    'days' => $currentDate->diffInDays($batchNo['exp_date']),
                ];

                $batchNo['soon_expire_within'] = $soonExpireWithin;

                // if batch number, expiring within 1 month
                if( ($soonExpireWithin['days'] >= 1 && $soonExpireWithin['days'] <= 31) && $currentDate < $batchNo['exp_date'] && $batchNo['quantity'] > 0)
                {
                    array_push($soonExpiringProducts, $batchNo);
                }
            }
        }

        // set pages
        $this->setPages($soonExpiringProducts);

        return $soonExpiringProducts;
	}
}