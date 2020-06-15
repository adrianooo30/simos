<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;

// custom classes
use App\Dependency\ExpiredDependency;

// models
use App\Product;
use App\BatchNo;

// for plugin
use Yajra\Datatables\DataTables;

class ExpiredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $expiredDependency;

    public function __construct(ExpiredDependency $expiredDependency)
    {
        $this->expiredDependency = $expiredDependency;
    }

    public function index()
    {
        return DataTables::of( BatchNo::with(['product'])
                                    ->whereBetween('exp_date', [
                                        request()->query('from_date'),
                                        request()->query('to_date')
                                    ])
                                    ->whereExpired()
                                    ->where('quantity', '<>', 0)
                                )
                // add columns
                ->addColumn('product_img', function( $batchNo ){
                    return '<div align="center">
                                <img src="'.$batchNo->product['product_img'].'" alt="product_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->addColumn('product_name', function( $batchNo ){
                    return '<div>
                                <h5 class="text-darker">'.$batchNo->product['product_name'].'</h5>
                                <h6 class="text-muted">'.$batchNo->product['brand_name'].'</h6>
                            </div>';
                })
                // ->addColumn('batch_no', function( $batchNo ){
                //     return '<span class="font-12 font-weight-bold">'.$batchNo['batch_no'].' - '.number_format($batchNo['quantity']).' pcs.</span>';
                // })
                ->editColumn('after_expiring', function( $batch_no ){
                    return '<span class="text-primary">'.$batch_no['exp_date']->diffForHumans().'</span>';
                })
                ->editColumn('exp_date', function( $batch_no ){
                    return '<span>
                                <i class="ti-calendar text-danger"></i>
                                '.$batch_no['exp_date']->toDateString().'
                            </span>';
                })
                ->rawColumns(['product_img', 'product_name', 'batch_no', 'exp_date', 'after_expiring'])
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
}
