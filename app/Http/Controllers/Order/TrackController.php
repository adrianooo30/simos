<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;

use App\OrderTransaction;
use Illuminate\Http\Request;

// custom classes
use App\Dependency\TrackOrderDependency;
use App\Dependency\ProductDependency;

// for plugin
use Yajra\Datatables\DataTables;

// includes
use Illuminate\Support\Facades\View;

// notification generator
use App\CustomClasses\NotificationGenerator;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $trackOrderDependency;
    protected $productDependency;

    public function __construct(TrackOrderDependency $trackOrderDependency, ProductDependency $productDependency)
    {
        $this->trackOrderDependency = $trackOrderDependency;
        $this->productDependency = $productDependency;
    }

    public function index()
    {
        return DataTables::of( OrderTransaction::whereTrackOrders()
                                ->withDetails()
                                ->whereBetween('order_date', [ request()->query('from_date'), request()->query('to_date') ])
                                )
                // add columns
                ->addColumn('total_cost_format', function( $orderTransaction ){
                    return '<span class="font-weight-bolder text-primary">'.$orderTransaction->pesoFormat( $orderTransaction['total_cost'] ).'</span>';
                })
                ->addColumn('action', function( $orderTransaction ){
                    return '<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#pending-modal" onclick="orderDetails('.$orderTransaction['id'].')">
                                <i class="ti-shopping-cart"></i>
                            </button>';
                })
                ->addColumn('action', 'includes.datatables.status-options')
                // edit columns
                ->editColumn('profile_img', function($orderTransaction){
                    return '<div align="center">
                                <img src="'.$orderTransaction->account['profile_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->editColumn('account_name', function($orderTransaction){
                    return '<div>
                                <h5 class="text-primary">'.$orderTransaction->account['account_name'].'</h5>
                                <sup class="text-muted">'.$orderTransaction->account['type'].'</sup>
                            </div>';
                })
                ->editColumn('order_date', function($orderTransaction){
                    return '<i class="ti-calendar text-primary"></i> '.$orderTransaction['order_date'];
                })
                 ->addColumn('status_html', 'includes.datatables.status')
                // raw columns
                ->rawColumns(['profile_img', 'account_name', 'total_cost_format', 'status_html', 'order_date', 'action'])
                //convert to json
                ->toJson();
    }

    public function show(OrderTransaction $orderTransaction)
    {
        // get accout and  its details
        $track_order = $orderTransaction
                        ->fresh()
                        ->withDetails()
                        ->find( $orderTransaction['id'] );

        return response()->json([
            'order_details_html' => View::make('includes.track.order-details-modal', compact('track_order'))->render(),
            'track_order' => $track_order,
        ]);
    }

    // change status
    public function change_status(OrderTransaction $orderTransaction, Request $request)
    {
        $orderTransaction->update(['status' => $request->get('status')]);
    }

    // delivery
    public function delivery(OrderTransaction $orderTransaction, Request $request)
    {
        $validatedData = $request->validate([
            'receipt_no' => 'required|unique:deliver_transactions',
            'delivery_date' => 'required|date',
            'employee_id' => 'required|numeric',
        ]);

        $orderTransaction->deliverTransaction()->create($validatedData);

        return response()->json([
            'title' => 'Successfully Recorded',
            'text' => 'Successfully recorded the delivery details for this transaction.'
        ]);
    }

    // for order medicines datatables
    public function showOrders(OrderTransaction $orderTransaction)
    {
        // return order medicine in datatable
        return $orderTransaction->showOrderMedicine();
    }

}
