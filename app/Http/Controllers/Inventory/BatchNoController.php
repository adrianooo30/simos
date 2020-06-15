<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use App\Product;
use App\BatchNo;
use App\Supplier;

use Illuminate\Http\Request;
use App\Http\Requests\BatchNo\AddBatchNoRequest;
use App\Http\Requests\BatchNo\UpdateBatchNoRequest;

// use custom dependencies
use App\Dependency\BatchNoDependency;

// for plugin
use Yajra\Datatables\DataTables;

class BatchNoController extends Controller
{

    protected $batchNoDependency;
    
    public function __construct(BatchNoDependency $batchNoDependency)
    {
        $this->batchNoDependency = $batchNoDependency;
    }

    public function index(Product $product)
    {
        // return $this->batchNoDependency->getBatchNos($product->batchNo);

        return DataTables::of( $product->batchNo() )
                // add columns
                ->addColumn('unit_cost_format', function( $batchNo ){
                    return '<span class=""> '.$batchNo->pesoFormat($batchNo['unit_cost']).' </span>';
                })
                ->addColumn('quantity_format', function( $batchNo ){
                    return '<span class=""> '.$batchNo->quantityFormat($batchNo['quantity']).' </span>';
                })
                ->addColumn('action', function( $batchNo ){

                    $actionHTML = '';

                    if( auth()->user()->can('edit_batch_number') ){
                        $actionHTML = '<button class="btn btn-primary waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-batch-no-modal" onclick="batchInfo(\''.$batchNo['id'].'\')">
                                    <i class="ti-pencil-alt"></i>
                                    </button>';
                    }

                    if( $batchNo['quantity'] > 0 && auth()->user()->can('record_loss') ) {
                        $actionHTML .= '<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#loss-product-modal" onclick="lossBatchNo('.$batchNo['id'].', '.$batchNo['quantity'].')">
                                            <i class="ti-direction"></i>
                                         </button>';
                    }

                    // return action buttons
                    return $actionHTML;
                })
                // edit columns
                ->editColumn('batch_no', function( $batchNo ){
                    return html()->span()
                                ->text($batchNo['batch_no']);
                })
                ->editColumn('exp_date', function( $batchNo ){
                    return html()->span()
                                ->html(
                                    html()->span()->class('ti-calendar text-danger font-weight-bolder').' '.
                                    $batchNo['exp_date']->toDateString()
                                );
                })
                // raw columns
                ->rawColumns(['batch_no', 'exp_date', 'quantity_format', 'unit_cost_format', 'action'])
                //convert to json
                ->toJson();
    }
    
    public function store(AddBatchNoRequest $request)
    {
        BatchNo::create($request->except(['batch_no_id']));
        
        // if their are to be replaced order batch number
        // $this->batchNoDependency->set_toBeReplaced_orderBatchNo($request->inpu('product_id'));

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added new batch numbers for this product.',
        ]);
    }

    public function show(BatchNo $batchNo)
    {
        return $this->batchNoDependency->getBatchNoDetails($batchNo);
    }

    public function update(UpdateBatchNoRequest $request, BatchNo $batchNo)
    {
        $batchNo->update($request->except(['product_id', 'batch_no_id']));

        return response()->json([
            'title' => 'Successfully Updated.',
            'text' => 'Successfully updated for any changes you make for this batch number.',
        ]);
    }

}
