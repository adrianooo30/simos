<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\ProductDependency;
use App\Dependency\ReturneeDependency;

// packages
use Carbon\Carbon;

// model class
use App\Product;

class LossDependency extends BroadDependency
{

	protected $productDependency;

    protected $returneeDependency;

	public function __construct()
	{
        $this->productDependency = new ProductDependency();
		$this->returneeDependency = new ReturneeDependency();
	}

	public function get_lossProducts()
	{
        $products = Product::orderBy('generic_name', 'asc')->get();

        $lossProducts = array();
        foreach($products as $product)
        {
            foreach($product->batchNo as $batchNo)
            {
                foreach($batchNo->loss as $loss){
                    $loss->batchNo;
                    $loss['product'] = $loss->batchNo->product;
                    // set well formatted details
                    $this->productDependency->setProduct_Formatted($loss['product']);
                    array_push($lossProducts, $loss);
                }
            }
        }

        // get LOSS PRODUCTS in RETURNEES
        foreach($this->returneeDependency->get_returneeProducts() as $returnee)
        {
            // make loss_date index
            $returnee['loss_date'] = $returnee['returnee_date'];
            // if reason is damage
            if($returnee['static_reason'] === 'damage')
                array_push($lossProducts, $returnee);
        }

        // set pages
        // $this->setPages($lossProducts);
        // return loss products
        return $lossProducts;
	}
}