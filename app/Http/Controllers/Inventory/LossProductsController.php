<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

use App\Product;
use App\BatchNo;
use App\Loss;

use Illuminate\Http\Request;

// custom classes
use App\Dependency\LossDependency;

// for plugin
use Yajra\Datatables\DataTables;

class LossProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(LossDependency $lossDependency)
    {
        $this->lossDependency = $lossDependency;
    }

    public function index()
    {
        return DataTables::of( Loss::with(['batchNo', 'batchNo.product'])->orderBy('id', 'desc')->get() )
                // add columns
                ->addColumn('product_name', function( $loss ){
                    return '<div>
                                <h6 class="text-primary">'.$loss['product']['product_name'].'</h6>
                                <sup class="text-muted">'.$loss['product']['brand_name'].'</sup>
                            </div>';
                })
                ->addColumn('batch_no', function( $loss ){
                    return '<span class="font-12 text-primary">'.$loss->batchNo['batch_no'].' - '.number_format($loss['quantity']).' pcs.</span>';
                })
                ->addColumn('product_img', function( $loss ){
                    return '<div align="center">
                                <img src="'.asset($loss['product']['product_img']).'" alt="product_img" class="image-50">
                            </div>';
                })
                // edit columns
                ->editColumn('loss_date', function( $loss ){
                    return '<span class="font-14 text-primary"> <i class="ti-calendar"></i> '.$loss['loss_date'].'</span>';
                })
                ->editColumn('reason', function( $loss ){
                    return '<div class="alert alert-danger font-14">
                                <strong>'.$loss['reason'].'</strong>
                            </div>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'batch_no', 'loss_date', 'reason'])
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

    public function recordLoss(BatchNo $batchNo, Request $request)
    {
        // validation
        $lossData = $request->validate([
            'loss_date' => 'required|date',
            'quantity' => 'required|numeric|min:1',
            'reason' => 'required',
        ]);

        // storing the loss data for this batch number
        $loss = $batchNo->loss()->create($lossData);

        // getting of updated quantity, since their are loss quantity
        $updatedQuantity = $batchNo['quantity'] - $loss['quantity'];
        // updating of quantity
        $batchNo->update([ 'quantity' => $updatedQuantity ]);

        // notifications
        NotificationGenerator::criticalStock($batchNo->product);
        NotificationGenerator::outOfStock($batchNo->product); 

        return response()->json([
            'title' => 'Successfully Recorded.',
            'text' => 'Loss is recorded successfully. You can see it under inventory management, then loss products.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function show(Loss $loss)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function edit(Loss $loss)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loss $loss)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loss $loss)
    {
        //
    }
}
