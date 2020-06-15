<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use App\BatchNo;

use App\Returnee;
use App\OrderTransaction;
use App\OrderMedicine;
use App\OrderBatchNo;

// for returns
use App\ReturnedOrderMedicine;
use App\ReturnedOrderBatchNo;

use Illuminate\Http\Request;

// custom dependency classes
use App\Dependency\BatchNoDependency;
use App\Dependency\ReturneeDependency;

// for plugin
use Yajra\Datatables\DataTables;

class ReturneeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $returneeDependency;

    protected $batchNoDependency;

    public function __construct(ReturneeDependency $returneeDependency, BatchNoDependency $batchNoDependency)
    {
        $this->returneeDependency = $returneeDependency;

        $this->batchNoDependency = $batchNoDependency;
    }

    public function index()
    {
        return DataTables::of( ReturnedOrderBatchNo::with([
                                'returnedOrderMedicine.product',
                                'batchNo',
                            ])->get() )
                // add columns
                ->addColumn('product_img', function( $returnedOrderBatchNo ){
                    return '<div align="center">
                                <img src="'.$returnedOrderBatchNo->returnedOrderMedicine->product['product_img'].'" alt="product_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->addColumn('product_name', function( $returnedOrderBatchNo ){
                    return '<div>
                                <h5 class="text-darker">'.$returnedOrderBatchNo->returnedOrderMedicine->product['product_name'].'</h5>
                                <h6 class="text-muted">'.$returnedOrderBatchNo->returnedOrderMedicine->product['brand_name'].'</h6>
                            </div>';
                })
                ->addColumn('batch_no', function( $returnedOrderBatchNo ){
                    return '<span class="font-12 font-weight-bold">'.$returnedOrderBatchNo->batchNo['batch_no'].' - '.number_format($returnedOrderBatchNo['quantity']).' pcs.</span>';
                })
                ->addColumn('returned_date', function( $returnedOrderBatchNo ){
                    return $returnedOrderBatchNo->returnedOrderMedicine['returned_date'];
                })
                ->addColumn('reason', function( $returnedOrderBatchNo ){
                    return '
                        <div class="alert alert-danger">
                            <strong>'.$returnedOrderBatchNo['reason'].'</strong>
                        </div>';
                })
                ->rawColumns(['product_img', 'product_name', 'batch_no', 'returned_date', 'reason'])
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
        // RECORDD RETURNS
        $orderTransaction = OrderTransaction::find( $request->get('order_transaction_id') );
        $returned_order_medicines = $request->get('returned_order_medicines');

        // RETURNED ORDER MEDICINE AND UPDATE QUATITIES FOR BOTH ORDER MEDICINCE AND ORDER BATCH NUMBER
        foreach($returned_order_medicines as $returned_order_medicine){
            // LOWER THE FREE QUANTTITY  FOR ORDER MEDICINE
            $updated_free_quantity = (int)(OrderMedicine::find( $returned_order_medicine['id'] )['free']) - (int)($returned_order_medicine['free']);
            // update order medicine...
            $order_medicine = OrderMedicine::find( $returned_order_medicine['id'] )->update([
                'free' => $updated_free_quantity,
            ]);

            // RECORD THE RETURNED ORDER MEDICINE
            $returnedOrderMedicine = $orderTransaction->returnedOrderMedicines()->create([
                'product_id' => $returned_order_medicine['product_id'],
                'status'  => $returned_order_medicine['status'],
                'returned_date' => $returned_order_medicine['returned_date']
            ]);

            // if status
            switch($returnedOrderMedicine['status'])
            {
                case 'Replace':
                    $returnedOrderMedicine->replace()->create([
                        'is_replace' =>  false,
                    ]);
                break;
                case 'Dont Replace':
                    // 
                break;
            }

            // returned_order_batch_no
            foreach($returned_order_medicine['order_batch_nos'] as $returned_order_batch_no){

                // LOWER THE QUANTITY OF ORDER BATCH NUMBER
                OrderBatchNo::find( $returned_order_batch_no['order_batch_no_id'] )->update([
                    'quantity' =>  (int)(OrderBatchNo::find($returned_order_batch_no['order_batch_no_id'])['quantity']) - (int)($returned_order_batch_no['quantity']),
                ]);

                // FIND BATCH NUMBER ID
                $batch_no_id = OrderBatchNo::find( $returned_order_batch_no['order_batch_no_id'] )['batch_no_id'];
                // record returned order batch number
                $returnedOrderMedicine->returnedOrderBatchNos()->create([
                    'batch_no_id' => $batch_no_id,
                    'quantity' => $returned_order_batch_no['quantity'],
                    'reason' => $returned_order_batch_no['reason'],
                ]);
            }

            // event - has new returned medicine...
            event( new \App\Events\HasNewReturnedMedicine( OrderMedicine::find( $returned_order_medicine['id'] ) ) );
        }

        // track status, then update...
        $orderTransaction->trackStatus();

        // response for successfully recorded.
        return response()->json([
            'title' => 'Successfully recorded.',
            'text' => 'Successfully recorded new returned products.',
        ]);
    }

    public function success(Returnee $returnee ,Request $request)
    {
        // REPLACE
        if($returnee['status'] === 'replace')
            $returnee->replace()->update([ 'success' => true ]);
        // RETURN PAYMENT
        else if($returnee['status'] === 'return payment')
            $returnee->returnPayment()->update([ 'success' => true ]);

        // response for successfully recorded.
        return response()->json([
            'title' => 'Successfully recorded.',
            'text' => 'This changes have been saved, it will appear that the returned product have been replaced.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Return  $return
     * @return \Illuminate\Http\Response
     */
    public function show(Returnee $returnee)
    {
        return $this->returneeDependency->get_returnedProductDetails($returnee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Return  $return
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
     * @param  \App\Return  $return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Return  $return
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderTransaction $orderTransaction)
    {
        //
    }
}
