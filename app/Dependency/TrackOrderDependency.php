<?php

namespace App\Dependency;

// custom class dependency
use App\Dependency\OrderDependency;

// model class
use App\OrderTransaction;

class TrackOrderDependency extends OrderDependency
{

	public function __construct()
	{
		//
	}

	public function getQuery()
	{
		return OrderTransaction::orderBy('id', 'desc')
                ->where('status', 'Pending')
                ->orWhere('status', 'Approved')
                ->orWhere('status', 'Canceled')
                ->orWhere('status', 'Delivered')
                ->get();
	}

	public function get_trackOrders($orderTransactions)
	{	
		// view order details
		$this->getOrderTransactions($orderTransactions);
		// return orderTransactions
		return $orderTransactions;
	}

	public function get_trackOrderDetails($orderTransaction)
	{
        // get deliver transaction - and employee
        $orderTransaction->deliverTransaction;
		// get ORDER TRANSACTION - with its unified data
		$this->get_orderTransactionDetails($orderTransaction);
		// return order transaction
		return $orderTransaction;
	}

	// datatables in modal
	public function showOrders(OrderTransaction $orderTransaction)
    {
        return DataTables::of( $orderTransaction->orderMedicine )
                // add columns
                ->addColumn('product_img', function( $orderMedicine ){
                    return '<div align="center">
                                <img src="'.$orderMedicine->product['product_img'].'" alt="product_img" class="image-50">
                            </div>';
                })
                ->addColumn('product_name', function( $orderMedicine ){
                    return '<div>
                                <h6 class="text-primary">'.$orderMedicine->product['generic_name'].' '.$orderMedicine->product['strength'].'</h6>
                                <sup class="text-muted">'.$orderMedicine->product['brand_name'].'</sup><br>
                                <sup class="text-muted">'.$this->productDependency->setProduct_Formatted($orderMedicine->product)['unit_price_format'].'</sup>
                            </div>';
                })
                ->addColumn('batch_nos', function( $orderMedicine ){
                    $listBatchNos = '';
                    foreach( $this->viewOrderDependency->getOrderBatchNos( $orderMedicine->orderBatchNo ) as $orderBatchNo )
                    {
                        $listBatchNos .= '<span class="text-primary">'.$orderBatchNo->batchNo['batch_no'].'</span> - <span>'.$orderBatchNo['quantity_format'].'</span><br>';
                    }

                    // list of batch numbers
                    return $listBatchNos;
                })
                ->addColumn('quantity', function( $orderMedicine ){
                    return intval( $this->viewOrderDependency->set_orderMedicine_Formatted($orderMedicine)['quantity'] );
                })
                ->addColumn('quantity_format', function( $orderMedicine ){
                    return number_format( $this->viewOrderDependency->set_orderMedicine_Formatted($orderMedicine)['quantity'] ).' pcs.';
                })
                 ->addColumn('total_cost', function( $orderMedicine ){
                    return intval( $this->viewOrderDependency->set_orderMedicine_Formatted($orderMedicine)['total_cost'] );
                })
                ->addColumn('total_cost_format', function( $orderMedicine ){
                    return '<span class="text-primary">'.$this->viewOrderDependency->set_orderMedicine_Formatted($orderMedicine)['total_cost_format'].'</span>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'batch_nos', 'quantity_format', 'total_cost_format', 'action'])
                //convert to json
                ->toJson();
    }

}