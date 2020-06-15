<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// custom classes
use App\Dependency\SoonExpiringDependency;

// models
use App\Product;
use App\BatchNo;

// for plugin
use Yajra\Datatables\DataTables;

class SoonExpiringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $soonExpiringDependency;

    public function __construct(SoonExpiringDependency $soonExpiringDependency)
    {
        $this->soonExpiringDependency = $soonExpiringDependency;
    }

    public function index()
    {
        return DataTables::of( BatchNo::with(['product'])
                                ->whereSoonExpiring()
                                ->where('quantity', '<>', 0)
                            )
                // add columns
                ->addColumn('product_img', function( $batch_no ){
                    return '<div align="center">
                                <img src="'.$batch_no['product']['product_img'].'" alt="product_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->addColumn('product_name', function( $batch_no ){
                    return '<div>
                                <h5 class="text-darker">'.$batch_no['product']['product_name'].'</h5>
                                <h6 class="text-muted">'.$batch_no['product']['brand_name'].'</h6>
                            </div>';
                })
                // edit columns
                ->addColumn('before_expiring', function( $batch_no ){
                    return '<span class="text-primary">'.$batch_no['exp_date']->diffForHumans().'</span>';
                })
                ->editColumn('exp_date', function( $batch_no ){
                    return '<span>
                                <i class="ti-calendar text-danger"></i>
                                '.$batch_no['exp_date']->toDateString().'
                            </span>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'exp_date', 'before_expiring'])
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
