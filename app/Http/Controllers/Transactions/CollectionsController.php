<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;

use App\CollectionTransaction;
use App\OrderTransaction;
use App\Deposit;
use Illuminate\Http\Request;

use App\Dependency\CollectionDependency;
use App\Dependency\EmployeeDependency;

// for plugin
use Yajra\Datatables\DataTables;

// includes
use Illuminate\Support\Facades\View;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $collectionDependency;
    protected $employeeDependency;

    public function __construct(CollectionDependency $collectionTransaction, EmployeeDependency $employeeDependency)
    {
        $this->collectionDependency = $collectionTransaction;
        $this->employeeDependency = $employeeDependency;
    }

    public function index(Request $request)
    {
        return DataTables::of( CollectionTransaction::with(['employee'])
                                ->withTotalCollectedAmount()
                                ->orderBy('id', 'desc')
                                ->whereBetween('collection_date', [ $request->query('from_date'), $request->query('to_date') ])
                                ->where('total_collected_amount', '>', 0)
                            )
                // add columns
                ->addColumn('receipt_no', function( $collectionTransaction ){
                    return '<span class="badge badge-success font-12">
                                <i class="ti-receipt"></i> '.$collectionTransaction['receipt_no'].'
                            </span>';
                })
                ->addColumn('collected_by', function( $collectionTransaction ){
                    // return employee's full name
                    return $collectionTransaction['employee']['full_name'];
                })
                ->addColumn('action', function( $collectionTransaction ){
                    // return action - opening of modal
                    return '<div class="d-flex" align="center">
                                <button class="btn btn-primary waves-effect wave-light mx-2" data-toggle="modal" data-target="#collections-modal" onclick="orderDetails('.$collectionTransaction['id'].')">
                                    <i class="ti-shopping-cart"></i>
                                </button>
                            </div>';
                })
                ->addColumn('profile_img', function( $collectionTransaction ){

                    return '
                        <div align="center">
                            <img src="'.$collectionTransaction->account['profile_img'].'" alt="profile_img" class="img-thumbnail image-50">
                        </div>';
                })
                ->addColumn('account_name', function( $collectionTransaction ){

                    return '
                        <div>
                            <h5 class="text-primary">'.$collectionTransaction->account['account_name'].'</h5>
                            <sup class="text-muted">'.$collectionTransaction->account['type'].'</sup>
                        </div>';
                })
                ->addColumn('total_collected_amount', function( $collectionTransaction ){
                    return $collectionTransaction['total_collected_amount'];
                })
                ->addColumn('total_collected_amount_format', function( $collectionTransaction ){
                    // return 
                    return '<span class="text-primary font-weight-bolder">'.$collectionTransaction->pesoFormat($collectionTransaction['total_collected_amount']).'</span>';
                })
                // edit columns
                ->addColumn('collection_date', function( $collectionTransaction ){
                    // return 
                    return '<span>
                                <i class="ti-calendar text-primary"></i>
                                '.$collectionTransaction['collection_date'].'
                            </span>';
                })
                // raw columns
                ->rawColumns(['receipt_no', 'profile_img', 'account_name', 'collection_date', 'total_collected_amount_format', 'action'])
                //convert to json
                ->toJson();
    }

    public function show(CollectionTransaction $collectionTransaction)
    {
        // $collection_transaction = $this->collectionDependency->get_collectionDetails($collectionTransaction);

        $collection_transaction = $collectionTransaction
                                    ->withTotalCollectedAmount()
                                    ->find( $collectionTransaction['id'] );

        return response()->json([
            'collections_html' => View::make('includes.collection.collection-modal-details', compact('collection_transaction'))->render(),
            'collections_transaction' => $collection_transaction,
        ]);
    }

    public function deposit(CollectionTransaction $collectionTransaction, Request $request)
    {
        $validateDeposit = $request->validate([
            'bank' => 'required',
            'date_of_deposit' => 'required|date',
            'employee_id' => 'required|numeric',
        ]);

        if($request->get('add')){// if for adding
            $collectionTransaction->deposit()->create($validateDeposit);

            return response()->json([
                'title' => 'Successfully Recorded.',
                'text' => 'Deposit details for this collection has been saved.',
            ]);
        }
        else if($request->get('update')){
            Deposit::find($request->get('deposit_id'))->update($validateDeposit);
            
            return response()->json([
                'title' => 'Successfully Updated.',
                'text' => 'Any changes you made from this transactions\' deposit details has been saved.',
            ]);
        }

    }

    // ORDER TRANSACTION - STATUS OF PARTIALLY PAID AND FULLY PAID
    public function getPaidBills(CollectionTransaction $collectionTransaction)
    {
        return DataTables::of( $collectionTransaction->getOrderTransactions() )
                ->addColumn('receipt_no', function( $orderTransaction ){
                    return '<span class="badge badge-primary font-12"> <i class="ti-receipt"></i> '.$orderTransaction->deliverTransaction['receipt_no'].'</span>';
                })
                ->addColumn('total_cost_format', function( $orderTransaction ) use ($collectionTransaction){
                    return '<span class="text-primary font-weight-bolder">'.$orderTransaction->pesoFormat($orderTransaction['total_cost']).'</span>';
                })
                ->addColumn('total_paid_amount_format', function( $orderTransaction ) use ($collectionTransaction){
                    return '<span class="text-success font-weight-bolder">'.$collectionTransaction->getTotalPaidFor($orderTransaction['id'])['total_paid_format'].'</span>';
                })
                ->addColumn('action', function( $orderTransaction ) use ($collectionTransaction){
                    return '<button class="btn btn-primary waves-effect waves-light --order-medicine-action-btn" data-toggle="modal" data-target="#order-medicine-paid-modal" data-order-transaction-id="'.$orderTransaction['id'].'" data-collection-transaction-id="'.$collectionTransaction['id'].'">
                                <i class="ti-shopping-cart"></i>
                            </button>';
                })
                // edit columns
                // ->editColumn('profile_img', 'includes.datatables.profile-image')
                // raw columns
                ->rawColumns(['receipt_no', 'others', 'total_cost_format', 'total_paid_amount_format', 'action'])
                //convert to json
                ->toJson();
    }

    // set... bwesiiittttt....
    public function charts(Request $request)
    {
        // COLLECTIONS PER PRODUCT
        $collectionsPerProduct = \App\Product::getCollectionsPerProduct($request);

        // COLLECTIONS PER ACCOUNT
        $collectionsPerAccount = \App\Account::getCollectionsPerAccount($request);

        // return chart report
        return [
            'collections_per_product' => [
                'product_names' => $collectionsPerProduct->pluck('product_name'),
                'collection_totals' => $collectionsPerProduct->pluck('total_collections'),
            ],
            'collections_per_account' => [
                'account_names' => $collectionsPerAccount->pluck('account_name'),
                'collection_totals' => $collectionsPerAccount->pluck('total_collections'),
            ],
        ];
    }

    public function paidOrderMedicines()
    {
        $collectionTransaction = CollectionTransaction::find( request()->query('collection_transaction_id') );

        return DataTables::of( $collectionTransaction->getPaidOrderMedicineFor( request()->query('order_transaction_id') ))
                // add columns
                ->addColumn('product_img', function($orderMedicine){
                    return '<div align="center">
                                <img src="'.$orderMedicine['product']['product_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->addColumn('product_name', function($orderMedicine){
                    return '<div>
                                <h5 class="text-darker">'.$orderMedicine['product']['product_name'].'</h5>
                                <h6 class="text-muted">'.$orderMedicine['product']['brand_name'].'</h6>
                                <h6 class="text-muted">'.$orderMedicine->pesoFormat($orderMedicine['unit_price']).'</h6>
                            </div>';
                })
                // edit columns
                ->editColumn('paid_quantity_format', function($orderMedicine){
                    return '<span class="font-weight-bolder text-muted">'.$orderMedicine->quantityFormat($orderMedicine['paid']['paid_quantity']).'</span>';
                })
                ->editColumn('paid_amount_format', function($orderMedicine){
                    return '<span class="font-weight-bolder text-muted">'.$orderMedicine->pesoFormat($orderMedicine['paid']['paid_amount']).'</span>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'paid_quantity_format', 'paid_amount_format', ])
                //convert to json
                ->toJson();
    }
}
