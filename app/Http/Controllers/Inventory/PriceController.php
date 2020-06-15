<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use App\Product;
use App\Price;

use Illuminate\Http\Request;

// custom dependency class
use App\Dependency\PriceDependency;

// for plugin
use Yajra\Datatables\DataTables;

class PriceController extends Controller
{

    protected $priceDependency;

    public function __construct(PriceDependency $priceDependency)
    {
        $this->priceDependency = $priceDependency;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DataTables::of( Price::all() )
                // add columns
                ->addColumn('product_img', function( $price ){
                    return '<div align="center">
                                <img src="'.$price->product['product_img'].'" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->addColumn('product_name', function( $price ){
                    return '<div>
                                <h5 class="text-darker">'.$price->product['product_name'].'</h5>
                                <h6 class="text-muted">'.$price->product['brand_name'].'</h6>
                            </div>';
                })
                ->addColumn('action', function($price){
                    return '
                        <div class="d-flex">
                            <button class="btn btn-primary waves-effect wave-light mx-2" data-toggle="modal" data-target="#edit-price-modal" onclick="getPriceDetails('.$price['id'].')">
                                <i class="ti-more-alt"></i>
                            </button>
                        </div>';
                })
                // edit columns
                ->editColumn('unit_price_format', function($price){
                    return '<div class="alert alert-primary">
                                <strong>&#8369; '.number_format($price['unit_price'], 2).'</strong>
                            </div>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'unit_price_format', 'action',])
                //convert to json
                ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'unit_price' => 'required|numeric',
            'accountIds' => 'required|array',
        ]);

        // get product
        $product = Product::find( $request->input('product_id') );

        // create promo
        $price = $product->prices()->create([
            'unit_price' => $request->input('unit_price'),
        ]);

        // loop through accounts - attaching accounts to price
        foreach($request->input('accountIds') as $accountId) {
            $price->accounts()->attach($accountId);
        }

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added new price.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        return $this->priceDependency->get_priceDetails($price);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        $request->validate([
            'unit_price' => 'required',
            'accountIds' => 'required|array',
        ]);

        // update buy and take
        $price->update([
            'unit_price' => $request->input('unit_price'),
        ]);

        // detach all accounts
        $price->accounts()->detach();
        // loop through account ids
        foreach( $request->input('accountIds') as $accountId ) {
            $price->accounts()->attach($accountId);
        }

        // return response success
        return response()->json([
            'title' => 'Successfully Updated.',
            'text' => 'Successfully updated promo.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }

    // THIRD PARTY CONNECTIONS - PRODUCTS
    public function products()
    {
        return $this->priceDependency->get_availableProductsForCreatingPrice();
    }
}
