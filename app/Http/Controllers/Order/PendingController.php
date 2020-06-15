<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;

use App\OrderTransaction;
use App\OrderMedicine;
use Illuminate\Http\Request;

// for plugin
use Yajra\Datatables\DataTables;

// includes
use Illuminate\Support\Facades\View;

use DB;

class PendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DataTables::of( OrderTransaction::wherePendingOrders()
                                ->withDetails()
                                ->whereBetween('order_date', [ request()->query('from_date'), request()->query('to_date') ])
                            )
                ->addColumn('total_cost_format', function( $orderTransaction ){
                    return '<span class="font-weight-bolder text-primary">'.$orderTransaction->pesoFormat( $orderTransaction['total_cost'] ).'</span>';
                })
                ->addColumn('action', function( $orderTransaction ){
                    return '<button class="btn btn-primary waves-effect waves-light mx-2" data-toggle="modal" data-target="#pending-modal" onclick="orderDetails('.$orderTransaction['id'].')">
                                <i class="ti-shopping-cart"></i>
                            </button>';
                })
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
                 ->editColumn('status', 'includes.datatables.status')
                // raw columns
                ->rawColumns(['profile_img', 'account_name', 'total_cost_format', 'status', 'order_date', 'action'])
                //convert to json
                ->toJson();
    }

    public function show(OrderTransaction $orderTransaction)
    {
        // get accout and  its details
        $pending_order = $orderTransaction
                        ->fresh()
                        // ->with(['account'])
                        ->withDetails()
                        ->find($orderTransaction['id']);

        // return html and data
        return response()->json([
            'order_details_html' => View::make('includes.pending.order-details-modal', compact('pending_order'))->render(),
            'pending_order' => $pending_order,
        ]);
    }

    public function showOrders(OrderTransaction $orderTransaction)
    {
        // return order medicine in datatable
        return $orderTransaction->showOrderMedicine();
    }

}
