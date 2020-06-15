<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\OrderTransaction;
use App\BatchNo;

use App\Account;
use App\Product;

use App\ReturnedOrderMedicine;
use App\ReturnedOrderBatchNo;

use Illuminate\Http\Request;

use App\Dependency\BatchNoDependency;

use Illuminate\Database\Eloquent\Builder;

use App\Dependency\PriceDependency;

// for plugin
use Yajra\Datatables\DataTables;

class ReplacementController extends Controller
{

    protected $batchNoDependency;

    protected $priceDependency;

    public function __construct(BatchNoDependency $batchNoDependency, PriceDependency $priceDependency)
    {
        $this->batchNoDependency = $batchNoDependency;
        $this->priceDependency = $priceDependency;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // GET ALL ORDER TRANSACTIONS WITH RETURNED ORDER MEDICINE...
        $re_orderTransactions = OrderTransaction::whereHas('returnedOrderMedicines', function(Builder $query){
                                    return $query->where('status', 'Replace');
                                })->with([
                                    'returnedOrderMedicines.replace',
                                    'returnedOrderMedicines.product',
                                    'account',
                                    'deliverTransaction',
                                ])->get();

        // storage
        $to_replaced_orderTransactions = [];

        // GET RETURNED BATCH  NUMBERS,  THAT IS NOT ALREADY REPLACED...
        foreach($re_orderTransactions as $re_orderTransaction){
            $total_cost = 0;

            $is_has_to_be_replace = false;
            foreach($re_orderTransaction->returnedOrderMedicines as $returned_order_medicine){
                if($returned_order_medicine['replace'] != null){
                    if(!$returned_order_medicine->replace['is_replace']){
                        $total_cost += $returned_order_medicine->product['unit_price'] * $returned_order_medicine->returnedOrderBatchNos->sum('quantity');
                        $is_has_to_be_replace = true;
                    }
                }
            }
            // total cost
            $re_orderTransaction['total_cost'] = $total_cost;
            // is has to be replace
            if($is_has_to_be_replace)
                // array push...
                array_push($to_replaced_orderTransactions, $re_orderTransaction);
        }

        // set datatables
        return DataTables::of( $to_replaced_orderTransactions )
                // add columns
                ->addColumn('profile_img', function($re_orderTransaction){
                    return '<div align="center">
                                <img src="'.$re_orderTransaction->account['profile_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->addColumn('account_name', 'includes.datatables.name')
                ->editColumn('receipt_no', function( $re_orderTransaction ){
                    return '<span class="badge badge-primary font-12">
                            <i class="ti-receipt"></i>
                            '.$re_orderTransaction['deliverTransaction']['receipt_no'].'
                            </span>';
                })
                ->addColumn('total_cost_format', function( $re_orderTransaction ){
                    return '<div class="alert alert-primary"> <strong>&#8369; '.number_format($re_orderTransaction['total_cost'], 2).'</strong></div>';
                })
                ->addColumn('action', function( $re_orderTransaction ){
                    return '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-warning waves-effect wave-light mx-1" data-toggle="modal" data-target="#re-deliver-modal" onclick="details('.$re_orderTransaction['id'].', '.$re_orderTransaction->account['id'].')">
                                <i class="ti-shopping-cart"></i>
                            </button>
                            <button class="btn btn-danger waves-effect wave-light mx-1 --delete-btn" data-order-transaction-id="'.$re_orderTransaction['id'].'">
                                <i class="ti-trash"></i>
                            </button>
                        </div>';
                })
                // raw columns
                ->rawColumns(['receipt_no', 'profile_img', 'account_name', 'total_cost_format', 'action',])
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
        // VALIDATE
        $validatedData = $request->validate([
            'receipt_no' => 'required|unique:deliver_transactions',
            'delivery_date' => 'required|date',
            'employee_id' => 'required|numeric',
        ]);

        // CREATE ORDER TRANSACTION
        $orderTransaction = OrderTransaction::create( $request->input('order_transaction') );

        $orderTransaction->deliverTransaction()->create($validatedData);

        // ORDERED MEDICINES
        foreach( $request->input('order_medicine') as $orderedProduct )
        {
            // ORDER MEDICINE
            $orderMedicine = $orderTransaction->orderMedicine()->create([
                'product_id' => $orderedProduct['product_id'],
                'unit_price' => $this->priceDependency->getPriceFor(
                                    // pass account - with a type of Account model
                                    Account::find( $orderTransaction['account_id'] ),
                                    // pass product - with a type of Product model
                                    Product::find( $orderedProduct['product_id'] )
                                )['unit_price'],
                'free' => 0,
            ]);
            // GET THE USED BATCH NUMBER
            foreach($orderedProduct['used_batch_nos'] as $used_batch_no) // used_batchNo
            {
                // order medicine
                $orderMedicine->orderBatchNo()->create([
                    'batch_no_id' => $used_batch_no['batch_id'],
                    'quantity' => $used_batch_no['batch_quantity'],
                ]);
                // udpate the quantity
                BatchNo::find($used_batch_no['batch_id'])
                        ->update([
                            'quantity' => (int)(BatchNo::find($used_batch_no['batch_id'])['quantity']) - (int)($used_batch_no['batch_quantity']),
                        ]);           
            }
            // UPDATE THE RETURNED ORDER MEDICINE - replace status to true!!!!
            foreach($orderedProduct['returned_order_medicine_ids'] as $returned_order_medicine_id)
                ReturnedOrderMedicine::find( $returned_order_medicine_id )->replace()->update([
                    'is_replace' => true,
                ]);
        }

        return response()->json([
            'title' => 'Successfully Created.',
            'text' => 'Successfully created new order.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderTransaction  $orderTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(OrderTransaction $orderTransaction)
    {
        $to_replace_medicines = [];

        // GET RETURNED BATCH  NUMBERS,  THAT IS NOT ALREADY REPLACED...
        foreach($orderTransaction->returnedOrderMedicines as $returned_order_medicine){
            $is_not_replace = false;
            if($returned_order_medicine['replace'] != null && $returned_order_medicine['status'] == 'Replace'){
                if(!$returned_order_medicine->replace['is_replace']){
                    $is_not_replace = true;
                }
            }

            if($is_not_replace)
                // array push
                array_push($to_replace_medicines, $returned_order_medicine);
        }

        // merging products and its quantity

        // getting all unique product ids
        $product_ids = collect($to_replace_medicines)->map(function($to_replaced_medicine){
            return (int)($to_replaced_medicine['product_id']);
        })->unique();

        // MERGING PRODUCTS
        $replacement_products = [];
        foreach($product_ids as $product_id){
            // finding product, can be redundant...
            $replacement_product = collect($to_replace_medicines)
                            ->where('product_id', $product_id);

            // get returned order medicine...
            $returned_order_medicine_ids = $replacement_product->pluck('id');

            // product total quantity
            $product_total_quantity = $replacement_product
                                // get total quantity - of to be replace products
                                ->map(function($replaced_medicine){
                                    return $replaced_medicine
                                        ->returnedOrderBatchNos
                                        ->sum('quantity');
                                })
                                ->sum();

            $replacement_product = $replacement_product->first();
            // returned order medicine ids
            $replacement_product['returned_order_medicine_ids'] = $returned_order_medicine_ids;
            // set total quantity of returned order medicine via returned order batch number
            $replacement_product['total_quantity_format'] = number_format($product_total_quantity).' pcs.';
            // set used batch  numbers to current
            $replacement_product['used_batch_nos'] = $this->batchNoDependency->get_usedBatchNos($product_id, $product_total_quantity);

            // array push
            array_push( $replacement_products, $replacement_product);
        }

        // return datatables
        return DataTables::of( $replacement_products )
                // add columns
                ->addColumn('product_img', function($replacement_product) use ($orderTransaction){

                    $table_data = '';

                    if( count($replacement_product['used_batch_nos']) > 0 )
                    {
                        // this can be redundant...
                        $table_data .= html()->hidden('order_transaction_order_date')
                                        ->class(['--order_transaction_order_date'])
                                        ->value($orderTransaction['order_date']);

                        foreach($replacement_product['returned_order_medicine_ids'] as $returned_order_medicine_id){
                            $table_data .= html()->hidden('returned_order_medicine'.$returned_order_medicine_id.'')
                                                ->value( $returned_order_medicine_id )
                                                ->class(['--returned-order-medicine-id']);
                        }
                    }

                    $table_data .= '<div align="center">
                                        <input type="hidden" class="--medicine for-re-delivery" value="'.$replacement_product->product['id'].'">
                                        <img src="'.$replacement_product->product['product_img'].'" class="img-thumbnail image-50">
                                    </div>';

                    return $table_data;
                })
                ->addColumn('product_name', function($replacement_product){
                    return '<div>
                                <h5 class="text-darker">'.$replacement_product->product['product_name'].'</h5>
                                <h6 class="text-muted">'.$replacement_product->product['brand_name'].'</h6>
                            </div>';
                })
                ->addColumn('batch_nos', function($replacement_product){

                    if(count($replacement_product['used_batch_nos']) == 0)
                        return '<span class="text-muted font-weight-bold">Not enough stock.</span>';

                    $batchNos = '';
                    foreach($replacement_product['used_batch_nos'] as $used_batchNo){
                        // 
                        $batch_no = BatchNo::find( $used_batchNo['batch_id'] )['batch_no'];
                        // 
                        $batchNos .= '<span class="text-darker font-12 --medicine-'.$replacement_product->product['id'].' for-re-delivery" data-batch-no-id="'.$used_batchNo['batch_id'].'" data-batch-no-quantity="'.$used_batchNo['batch_quantity'].'">'.$batch_no.' - '.number_format($used_batchNo['batch_quantity']).' pcs.</span><br>';           
                    }
                    return $batchNos;
                })
                ->addColumn('action', function( $replacement_product ){

                    if(count($replacement_product['used_batch_nos']) == 0)
                        return '<span class="text-muted font-weight-bold">Not enough stock.</span>';

                    return '<button type="button" class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target="#edit-batch-no-modal">
                                <i class="ti-pencil-alt"></i>
                            </button>';
                })
                ->editColumn('total_quantity_format', function( $replacement_product ){

                    if(count($replacement_product['used_batch_nos']) == 0)
                        return '<span class="text-muted font-weight-bold">Not enough stock.</span>';

                    return $replacement_product['total_quantity_format'];
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'batch_nos', 'total_quantity_format', 'action',])
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
        $orderTransaction->returnedOrderMedicines()->update([ 'status' => 'Rejected' ]);

        return response()->json([
            'title' => 'Successfully Rejected.',
            'text' => 'Successfully rejected the request of the customer for replacing the products.',
        ]);
    }
}
