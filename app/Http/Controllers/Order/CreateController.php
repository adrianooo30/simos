<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// built in classes
use Illuminate\Support\Facades\Auth;

// models
use App\OrderTransaction;
use App\BatchNo;

use App\Account;
use App\Employee;
use App\Product;

// custom classes
use App\Dependency\BatchNoDependency;
use App\Dependency\PriceDependency;

// includes
use Illuminate\Support\Facades\View;

use DB;

// notification generator
use App\CustomClasses\NotificationGenerator;

class CreateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $batchNoDependency;

    protected $priceDependency;

    public function __construct(BatchNoDependency $batchNoDependency, PriceDependency $priceDependency)
    {
        $this->batchNoDependency = $batchNoDependency;
        $this->priceDependency = $priceDependency;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderTransaction = OrderTransaction::create( $request->input('order_transaction') );

        // ORDERED MEDICINE
        foreach( $request->input('order_medicine') as $orderedProduct )
        {
            $orderMedicine = $orderTransaction->orderMedicine()->create([
                'product_id' => $orderedProduct['product_id'],
                'unit_price' => $this->priceDependency->getPriceFor(
                    // pass account - with a type of Account model
                    Account::find( $orderTransaction['account_id'] ),
                    // pass product - with a type of Product model
                    Product::find( $orderedProduct['product_id'] )
                )['unit_price'],
                'free' => $orderedProduct['free'],
            ]);

            // GET THE USED BATCH NUMBER
            $used_batchNos = $this->batchNoDependency->get_usedBatchNos($orderedProduct['product_id'], $orderedProduct['quantity'] + $orderedProduct['free']);

            foreach($used_batchNos as $used_batchNo) // used_batchNo
            {
                $orderMedicine->orderBatchNo()->create([
                    'batch_no_id' => $used_batchNo['batch_id'],
                    'quantity' => $used_batchNo['batch_quantity']
                ]);

                BatchNo::find($used_batchNo['batch_id'])
                        ->update([
                            'quantity' => $used_batchNo['batch_updated_qty']
                        ]);                
            }

            // for notifications
            event( new \App\Events\NewOrderCreatedEventNotification([
                'product' => $orderMedicine->product,
            ]) );
        }

        // generate notification for this single order transaction
        // NotificationGenerator::newOrder($orderTransaction);

        return response()->json([
            'title' => 'Successfully Created.',
            'text' => 'Successfully created new order.',
        ]);
    }

    // GET ACCOUNTS
    public function accounts(Request $request)
    {
        $accounts = Account::search( $request->input('search') )
                    ->paginate(12);

        return response()->json([
            'accounts_html' => View::make('includes.create-order.account-list', compact('accounts'))->render(),
            'accounts' => $accounts,
        ]);
    }

    // GET PRODUCTS
    public function products(Account $account)
    {
        // products in cart
        $products_in_cart = request()->input('products_in_cart');
        // search text
        $search_text = request()->input('search') ?? '';

        // return  $products_in_cart;

        // GET ALL PRODUCTS AND SET PROMOS - FOR THE SPECIFIED ACCOUNT
        $products = Auth::user()
                    ->products()
                    ->wherePivot('active', true)
                    ->whereLike(
                        ['generic_name', 'brand_name', 'strength', 'unit_price'],
                        $search_text,
                    )
                    ->with('deals')
                    ->paginate(12);

        // SET APPRORIATE DEALS FOR EACH SINGLE ACCOUNT
        foreach($products as $product) {
            // ---===>>>> set approriate deal for account
            foreach($product->deals as $deal) {
                // ---===>>>>
                foreach($deal->accounts as $acc) {
                    // ---===>>>>
                    if($acc['id'] == $account['id']) {
                        $product['approriate_deal'] = $deal;
                    }
                }
            }

            // ---===>>>> set approriate price of product for account
            $price_of_product_for_account = $this->priceDependency->getPriceFor(
                                            // pass account - with a type of Account model
                                            $account,
                                            // pass product - with a type of Product model
                                            $product
                                        );
            // setting unit price and its format
            $product['unit_price'] = $price_of_product_for_account['unit_price'];
            $product['unit_price_format'] = $price_of_product_for_account['unit_price_format'];

        }

        // RETURN VIEW RENDERED
        return response()->json([
            'products_html' => View::make(
                'includes.create-order.product-list',
                compact([
                    'products', 'products_in_cart', 'search_text'
                ])
            )->render(),
            'products' => $products,
        ]);
    }

}
