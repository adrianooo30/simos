<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// model
use App\OrderTransaction;
use App\DeliverTransaction;

// custom classes
use App\Dependency\SalesDependency;
use App\Dependency\CollectionDependency;

// for plugin
use Yajra\Datatables\DataTables;

// for filtering
use Carbon\Carbon;

// includes
use Illuminate\Support\Facades\View;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $salesDependency;
    protected $collectionDependency;

    public function __construct(SalesDependency $salesDependency, CollectionDependency $collectionDependency)
    {
        $this->salesDependency = $salesDependency;
        $this->collectionDependency = $collectionDependency;
    }

    public function index(Request $request)
    {
        return DataTables::of( OrderTransaction::whereSales()
                                            ->withDetails()
                                            ->whereBetween('delivery_date', [
                                                    request()->query('from_date'),
                                                    request()->query('to_date')
                                            ])
                                        )
                // row manipulation
                // add columns
                ->addColumn('total_cost_format', function( $orderTransaction ){
                    return '<span class="font-weight-bolder text-primary">'.$orderTransaction->pesoFormat( $orderTransaction['total_cost'] ).'</span>';
                })
                ->addColumn('action', function($orderTransaction){
                    // return order transactions...
                    return '
                        <div class="d-flex">
                            <button class="btn btn-warning waves-effect wave-light mx-2" data-toggle="modal" data-target="#sales-modal" onclick="orderDetails('.$orderTransaction['id'].')">
                                <i class="ti-shopping-cart"></i>
                            </button>
                        </div>';
                })
                // edit columns
                ->addColumn('receipt_no', function( $orderTransaction ){
                    $table_data = '';
                    if($orderTransaction['replacement'])
                        $table_data .= '<span class="badge badge-danger">RE</span>';
                    // table data...
                    $table_data .= '<span class="badge badge-primary font-12">
                                        <i class="ti-receipt"></i> '.$orderTransaction->deliverTransaction['receipt_no'].'
                                    </span>';
                    // return table data...
                    return $table_data;
                })
                ->addColumn('profile_img', function($orderTransaction){
                    return '<div align="center">
                                <img src="'.$orderTransaction->account['profile_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->addColumn('account_name', function($orderTransaction){
                    return '<div>
                                <h5 class="text-primary">'.$orderTransaction->account['account_name'].'</h5>
                                <sup class="text-muted">'.$orderTransaction->account['type'].'</sup>
                            </div>';
                })
                ->addColumn('delivery_date', function($orderTransaction){
                    return '<i class="ti-calendar text-primary"></i> '.$orderTransaction['delivery_date'];
                })
                // ->addColumn('delivery_date_id', function($orderTransaction){
                //     return '<i class="ti-calendar text-primary"></i> '.$orderTransaction['deliverTransa'];
                // })
                ->addColumn('status_html', function($orderTransaction){

                    switch($orderTransaction['status'])
                    {
                        case 'Delivered':
                            return '<button type="button" class="status-btn status-btn-danger waves-effect wave-light">Not Paid</button>';
                        break;
                        case 'Partially Paid':
                            return '<button type="button" class="status-btn status-btn-warning waves-effect wave-light">Partially Paid</button>';
                        break;
                        case 'Fully Paid':
                            return '<button type="button" class="status-btn status-btn-primary waves-effect wave-light">Fully Paid</button>';
                        break;
                    }

                })
                ->editColumn('status', function($orderTransaction){

                    if($orderTransaction['status'] == 'Delivered')
                        return  'Not Paid';

                    return $orderTransaction['status'];

                })
                // raw columns
                ->rawColumns(['receipt_no', 'profile_img', 'account_name', 'delivery_date', 'total_cost_format', 'status_html', 'action'])
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

    // responsible for storing of record of returnees
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(OrderTransaction $orderTransaction)
    {
        // order transaction
        $sales_transaction = OrderTransaction::withDetails()
                                ->withTotalBill()
                                ->withTotalPaidAmount()
                                ->find( $orderTransaction['id'] );

        // order medicine
        $sales_transaction['order_medicine'] = $orderTransaction
                                                ->orderMedicine()
                                                ->withDetails()
                                                ->get();

        return response()->json([
            'sales_transaction_html' => View::make('includes.sales.sales-modal-details', compact('sales_transaction'))->render(),
            'return_product_html' => View::make('includes.sales.return-product', compact('sales_transaction'))->render(),
            'sales_transaction' => $sales_transaction,
        ]);
    }

    //
    public function showOrders(OrderTransaction $orderTransaction)
    {
        return $orderTransaction->showOrderMedicine();
    }

    // requesting for sales that are going to return
    // public function toReturn(OrderTransaction $orderTransaction)
    // {
    //     $sales_transaction = $orderTransaction;

    //     return response()->json([
    //         'return_product_html' => View::make('includes.sales.return-product', compact('sales_transaction'))->render(),
    //         'return_product' => $sales_transaction,
    //     ]);
    // }

    public function replacedProduct(OrderTransaction $orderTransaction)
    {
        
    }

    public function getCollections(OrderTransaction $orderTransaction)
    {
        // return $this->collectionDependency->get_collectionsFor( $orderTransaction );

        return DataTables::of( $orderTransaction->collectionTransaction )
                // add columns
                ->addColumn('receipt_no', function( $collectionTransaction ){
                    return '<span class="font-12 text-primary">'.$collectionTransaction['receipt_no'].'</span>';
                })
                ->addColumn('paid_amount', function( $collectionTransaction ){
                    return $collectionTransaction->pivot['paid_amount'];
                })
                ->addColumn('paid_amount_format', function( $collectionTransaction ){
                    return '&#8369; '.number_format($collectionTransaction->pivot['paid_amount'], 2);
                })
                ->addColumn('type_of_payment', function($collectionTransaction){

                    switch($collectionTransaction->pivot['type_of_payment'])
                    {
                        case 'full':
                            return '<button class="status-btn status-btn-primary" disabled>Full Paid</button>';
                        break;
                        case 'partial':
                            return '<button class="status-btn status-btn-warning" disabled>Partially Paid</button>';
                        break;
                    }

                })
                // edit columns
                ->editColumn('collection_date', function($collectionTransaction){
                    return '<i class="ti-calendar text-primary"></i> '.$collectionTransaction['collection_date'];
                })
                // raw columns
                ->rawColumns(['receipt_no', 'collection_date', 'paid_amount_format', 'type_of_payment'])
                //convert to json
                ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderTransaction  $orderTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderTransaction $orderTransaction)
    {
        //
    }

    // set... bwesiiittttt....
    public function charts(Request $request)
    {
        $salesPerProduct = \App\Product::getSalesPerProduct( $request );

        $salesPerAccount = \App\Account::getSalesPerAccount( $request );

        // return chart report
        return [
            'sales_per_product' => [
                'product_names' => $salesPerProduct->pluck('product_name'),
                'sales_totals' => $salesPerProduct->pluck('total_sales'),
            ],
            'sales_per_account' => [
                'account_names' => $salesPerAccount->pluck('account_name'),
                'sales_totals' => $salesPerAccount->pluck('total_sales'),
            ],
        ];
    }
}
