<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// custom dependency class
use App\Dependency\PromoDependency;

// model classes
use App\Product;

use App\Deal;

// for plugin
use Yajra\Datatables\DataTables;

class DealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $promoDependency;

    public function __construct(PromoDependency $promoDependency)
    {
        $this->promoDependency = $promoDependency;
    }

    public function index()
    {
        // return all the promos
        return DataTables::of( Deal::with(['product'])->get() )
                // add columns
                ->addColumn('product_name', function( $deal ){
                    return '<div>
                                <h5 class="text-darker">'.$deal['product']['product_name'].'</h5>
                                <h6 class="text-muted">'.$deal['product']['brand_name'].'</h6>
                            </div>';
                })
                ->addColumn('action', function( $deal ){
                    $actionHTML = '';

                    if(auth()->user()->can('edit_deals')){
                        $actionHTML .= '<button type="button" class="btn btn-primary waves-effect waves-light mx-2" data-toggle="modal" data-target="#edit-deals-modal" onclick="getPromoDetails('.$deal['id'].')">
                                <i class="ti-more-alt"></i>
                            </button>';
                    }

                    return $actionHTML;
                })
                // edit columns
                ->editColumn('product_img', function( $deal ){
                    return '<div align="center">
                                <img src="'.$deal['product']['product_img'].'" alt="product_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('deal_name', function( $deal ){
                    return '<div class="alert alert-danger">
                                <strong>'.$deal['deal_name'].' pcs.</strong>
                            </div>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'deal_name', 'action'])
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
            'buy' => 'required|numeric',
            'take' => 'required|numeric',
            'accountIds' => 'required|array',
        ]);

        // get product
        $product = Product::find( $request->input('product_id') );

        // create promo
        $promo = $product->deals()->create([
            'buy' => $request->input('buy'),
            'take' => $request->input('take'),
        ]);

        // loop through accounts - attaching accounts to promo
        foreach($request->input('accountIds') as $accountId) {
            $promo->accounts()->attach($accountId);
        }

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added new promo.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal)
    {
        return $this->promoDependency->get_promoDetails($deal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        $request->validate([
            'buy' => 'required|numeric',
            'take' => 'required|numeric',
            'accountIds' => 'required|array',
        ]);

        // update buy and take
        $deal->update([
            'buy' => $request->input('buy'),
            'take' => $request->input('take'),
        ]);

        // detach all accounts
        $deal->accounts()->detach();
        // loop through account ids
        foreach( $request->input('accountIds') as $accountId ) {
            $deal->accounts()->attach($accountId);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // THIRD PARTY CONNECTIONS - PRODUCTS
    public function products()
    {
        return $this->promoDependency->get_availableProductsForCreatingPromo();
    }
}
