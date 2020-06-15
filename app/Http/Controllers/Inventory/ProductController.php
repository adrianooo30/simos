<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use App\Product;
use App\BatchNo;
use App\Supplier;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Http\Requests\Product\AddProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

// custom classes
use App\Dependency\ProductDependency;

use App\Dependency\EmployeeDependency;

// notification classes
use App\Notifications\OutOfStock;

use App\Employee;

// for plugin
use Yajra\Datatables\DataTables;

// includes
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // 
    }

    public function index()
    {
        return DataTables::of( Product::withStock() )
                // add columns
                ->addColumn('product_name', function( $product ){
                    return '<div>
                                <h5 class="text-darker">'.$product['product_name'].'</h5>
                                <h6 class="text-muted">'.$product['brand_name'].'</h6>
                            </div>';
                })
                ->addColumn('stock_format', function( $product ){
                    return $product->quantityFormat($product['stock']);
                })
                ->addColumn('unit_price_format', function( $product ){
                    return '<span class="text-darker">'.$product->pesoFormat($product['unit_price']).'</span>';
                })
                ->addColumn('action', function( $product ){
                    return '<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#product-details-modal" onclick="productInfo('.$product['id'].')">
                                <i class="ti-eye"></i>
                            </button>';
                })
                ->editColumn('stock', function( $product ){
                    return is_null($product['stock']) ? 0 : (int)($product['stock']);
                })
                // edit columns
                ->editColumn('product_img', function( $product ){
                    return '<div align="center">
                                <img src="'.$product['product_img'].'" alt="product_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'stock_format', 'unit_price_format', 'action', 'nbsp'])
                //convert to json
                ->toJson();
    }

    public function store(AddProductRequest $request)
    {
        // CREATE PRODUCT
        $product = Product::create( $request->except(['product_img_hidden']) );

        $this->__storeImage($product);

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added new product',
        ]);
    }
    
    public function show(Product $product)
    {
        $product['current_holder'] = $product->currentHolder;

        return [
            'product_html' => View::make('includes.product.product-details-modal', compact(['product']))->render(),
            'product' => $product,
        ];
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        // UPDATE PRODUCT
        $product->update($request->except(['product_img_hidden']));

        $this->__storeImage($product);

        return response()->json([
            'title' => 'Successfully Updated.',
            'text' => 'Successfully updated any changes you create.',
        ]);
    }

    private function __storeImage($product)
    {
        if( request()->hasFile('product_img') ){
            $product->update([
                'product_img' => '/storage/'.request()->file('product_img')->store('uploads', 'public'),
            ]);
        }
        else{
            $product->update([
                'product_img' => request()->input('product_img_hidden'),
            ]);
        }
    }


    // SET HOLDER
    public function holder(Product $product, Request $request)
    {
        $isHasTheEmployee = false;
        // LOOP THROUGH EMPLOYEES
        foreach($product->employee as $employee) {
            // if the employee already hold the product
            $request->input('holder') == $employee['id'] ? $isHasTheEmployee = true : '';
        }
        // IS HAS THE FORMER EMPLOYEE HOLDED BY
        if($isHasTheEmployee) {
            // SET ALL EMPLOYEE TO FALSE
            foreach($product->employee as $employee) {
                $employee->pivot->update([ 'active' => false ]);
            }
        }

        // RECORD PSR TO HOLD
        if( is_numeric($request->input('holder')) ) {
            //
            // if(  )
                $product->employee()->attach( $request->input('holder'), ['active' => true]);
        }
        // IF SELECTED NO HOLDER
        else if( !is_numeric($request->input('holder')) ){ // if $employeeId is 
            foreach($product->employee as $employee)
                $employee->pivot->update(['active' => false]);
        }

        return response()->json([
            'title' => 'Successfully Recorded.',
            'text' => 'Successfully recorded the current holder of product.',
        ]);
    }


    // DATATABLES FOR PRODUCTS FOR HOLDING
    public function getProductsForHolding()
    {
        return DataTables::of( Product::orderBy('generic_name')->get() )
                // add columns
                ->addColumn('checkbox', function( $product ){

                    return '<div class="custom-control custom-checkbox" id="">
                                <input type="checkbox" class="custom-control-input sub-checkbox-o" id="sub-checkbox-o-'.$product['id'].'" data-order-transaction-id="'.$product['id'].'">
                                <label class="custom-control-label sub-checkbox-o-label" for="sub-checkbox-o-'.$product['id'].'"></label>
                            </div>';
                })
                ->addColumn('product_name', function( $product ){

                    // GET PRODUCT DETAILS
                    $this->productDependency->getProductDetails($product);

                    return '<div class="d-flex">
                                <img src="'.$product['product_img'].'" alt="product_img" class="image-50 mx-3">

                                <div class="mx-3">
                                    <h6 class="text-primary">'.$product['generic_name&strength'].'</h6>
                                    <sup class="text-muted">'.$product['brand_name'].'</sup><br>
                                    <sup class="text-muted">'.$product['unit_price'].'</sup>   
                                </div>
                            </div>';
                })
                ->addColumn('holder', function( $product ){

                    // GET CURRENT HOLDER
                    $this->productDependency->get_currentHolder($product);

                    return '<div class="d-flex">
                                <img src="'.$product['holder']['profile_img'].'" alt="profile_img" class="image-50 mx-3">
                                <div class="mx-3">
                                    <h6 class="text-primary">'.$product['holder']['full_name'].'</h6>
                                    <sup class="text-muted">'.$product['holder']['type'].'</sup>   
                                </div>
                            </div>';
                })
                // edit columns
                // ->editColumn('address', 'includes.datatables.address')
                // raw columns
                ->rawColumns(['checkbox', 'product_name', 'holder',])
                //convert to json
                ->toJson();
    }
}
