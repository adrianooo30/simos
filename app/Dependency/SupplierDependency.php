<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Supplier;

class SupplierDependency extends BroadDependency
{

    public function __construct()
    {
        //
    }

    public function setSupplier()
    {
        //
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::orderBy('supplier_name', 'asc')->get();
        // set pages
        $this->setPages($suppliers);
        // return suppliers
        return $suppliers;
    }

    public function getSupplierDetails($supplier)
    {   
        // add custom attributes

        // return supplier
        return $supplier;
    }
}