<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Account;

use Illuminate\Http\Request;
use App\Http\Requests\Account\AddAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;

use App\Dependency\AccountDependency;

// for plugin
use Yajra\Datatables\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $accountDependency;

    public function __construct(AccountDependency $accountDependency)
    {
        $this->accountDependency = $accountDependency;
    }

    public function index()
    {
        return DataTables::of( Account::all() )
                // add columns
                ->addColumn('contact', 'includes.datatables.contact')
                ->addColumn('action', function($account){

                    $actionHTML = '';

                    if( auth()->user()->can('view_account_history') ){
                        $actionHTML .= '<a href="'.route('users.customers.history', ['account' => $account['id']]).'" class="btn btn-primary waves-effect waves-light mx-1">
                                            <i class="ti-bookmark-alt"></i>
                                        </a>';
                    }
                    if( auth()->user()->can('edit_account') ){
                        $actionHTML .= '<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-customer-account-modal" onclick="userDetails('.$account['id'].')">
                                            <i class="ti-pencil-alt"></i>
                                        </button>
                                    </div>';
                    }

                    return $actionHTML;

                })
                // edit columns
                ->editColumn('profile_img', function( $product ){
                    return '<div align="center">
                                <img src="'.$product['profile_img'].'" alt="profile_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('account_name', 'includes.datatables.name')
                ->editColumn('address', 'includes.datatables.address')
                // raw columns
                ->rawColumns(['profile_img', 'account_name', 'contact', 'address', 'action'])
                //convert to json
                ->toJson();
    }

    public function store(AddAccountRequest $request)
    {
        $account = Account::create( $request->except(['profile_img_hidden']) );

        $this->__storeImage($account);

        return response()->json([
            'title' => 'Successfully Added.',
            'text'  => 'Successfully added new account.',
            'account' => $account,
        ]);
    }

    public function show(Account $account)
    {
        return $this->accountDependency->getAccountDetails($account);
    }

    public function update(UpdateAccountRequest $request ,Account $account)
    {
        $account->update($request->except(['profile_img_hidden']));

        $this->__storeImage($account);

        return response()->json([
            'title' => 'Successfully Updated.',
            'text'  => 'Any changes you made have been saved.',
            'account' => $account,
        ]);
    }

    public function __storeImage($account)
    {
        if( request()->hasFile('profile_img') ){
            $account->update([
                'profile_img' => '/storage/'.request()->file('profile_img')->store('uploads', 'public'),
            ]);
        }
        else{
            $account->update([
                'profile_img' => request()->input('profile_img_hidden'),
            ]);
        }
    }

    public function history(Account $account)
    {
        $account = Account::with([
                    'orderTransaction',
                ])
                ->where('id', $account['id'])
                ->first();
        

        return view('users.customer-history', compact(['account']));
    }

    public function getHistory(Account $account)
    {
        // ****************************** //
        // $type = delivered or order     //
        // ****************************** //

        return $account->orderTransaction()
                        ->orWhere('status', 'Delivered')
                        ->orWhere('status', 'Balanced')
                        ->orWhere('status', 'Paid')
                        ->get();
    }

    // chart.js
    public function charts(Account $account)
    {
        $sales_per_product = $account->orderTransaction()
                ->whereSales()
                ->getSalesPerProduct()
                ->withDeliveryDate()
                ->whereBetween('delivery_date', [ request()->query('from_date'), request()->query('to_date') ])
                ->get()
                ->filter(function($product){
                    return !is_null($product['total_sales_quantity']);
                })
                ->transform(function($product){
                    return [
                        'product_name' => \App\Product::where('id', $product['id'])->first()['product_name'],
                        'total_sales_quantity' => $product['total_sales_quantity'],
                    ];
                });

        $collections_per_product = $account
                ->orderMedicinesThroughOrderTransaction()
                ->whereInCollectionDate( now()->startOfMonth(), now()->endOfMonth() )
                ->get()
                ->transform(function($product){
                    return [
                        'product_name' => \App\Product::where('id', $product['id'])->first()['product_name'],
                        'total_collections_quantity' => $product['total_collections_quantity'],
                    ];
                });

        return [
            // sales
            'sales' => [
                'product_name' => $sales_per_product->pluck('product_name'),
                'total_sales' => $sales_per_product->pluck('total_sales_quantity'),
            ],
            // collection
            'collections' => [
                'product_name' => $collections_per_product->pluck('product_name'),
                'total_collections' => $collections_per_product->pluck('total_collections_quantity'),
            ],
        ];
    }

    // 
    public function get_bills_can_paid_using_excess_payment(Account $account)
    {
        return DataTables::of( $account->get_bills_can_paid_using_excess_payment() )
                // add columns
                ->addColumn('total_bill_format', function( $orderTransaction ){
                    return '<span class="font-weight-bolder text-danger">'.$orderTransaction->pesoFormat( $orderTransaction['total_cost'] ).'</span>';
                })
                ->addColumn('total_bill', function( $orderTransaction ){
                    return $orderTransaction['total_cost'];
                })
                ->addColumn('action', function($orderTransaction){
                    // return order transactions...
                    return '
                        <button class="btn btn-primary waves-effect wave-light mx-2 --pay-btn" data-order-transaction-id="'.$orderTransaction['id'].'">
                            <i class="ti-shopping-cart"></i> PAY
                        </button>';
                })
                // edit columns
                ->addColumn('receipt_no', function( $orderTransaction ){
                    $table_data = '';
                    if($orderTransaction['replacement'])
                        $table_data .= '<span class="badge badge-danger">RE</span>';
                    // table data...
                    $table_data .= '<span class="badge badge-primary font-12">
                                        <i class="ti-receipt"></i> '.$orderTransaction['receipt_no'].'
                                    </span>';
                    // return table data...
                    return $table_data;
                })
                ->addColumn('delivery_date', function($orderTransaction){
                    return '<i class="ti-calendar text-primary"></i> '.$orderTransaction['delivery_date'];
                })
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
                ->rawColumns(['receipt_no', 'delivery_date', 'total_bill_format', 'status_html', 'action'])
                //convert to json
                ->toJson();
    }

}
