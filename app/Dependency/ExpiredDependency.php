<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\ProductDependency;

// packages
use Carbon\Carbon;

// model class
use App\Product;

class ExpiredDependency extends BroadDependency
{

	protected $productDependency;

	public function __construct()
	{
		$this->productDependency = new ProductDependency();
	}

	public function get_expiredProducts()
	{
        $expiredBatchNumbers = array();

        foreach(Product::all() as $product)
        {
            foreach($product->batchNo as $batchNo)
            {
                $currentDate = new Carbon();

                // get not expired
                if($currentDate >= $batchNo['exp_date']){
                    // get product
                    $this->productDependency->getProductDetails($batchNo->product);
                    // expired batch numbers
                    array_push($expiredBatchNumbers, $batchNo);
                }
            }
        }
        // set pages
        $this->setPages($expiredBatchNumbers);
        
        return $expiredBatchNumbers;
	}
}