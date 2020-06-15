<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// for plugin
use Yajra\Datatables\DataTables;

use DB;

class OrderTransaction extends Model
{
    use CustomTraits\FormatsTrait, CustomTraits\QueryDatatables;

    protected $guarded = [];

    protected $appends = [ 'excess_payment', ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('details', function (Builder $builder) {
    //         $builder->withDetails();
    //     });
    // }

    // RELATIONSHIP
    public function orderMedicine()
    {
    	return $this->hasMany(OrderMedicine::class);
    }

    // has many through
    public function collectionTransactions()
    {
        return $this->hasManyThrough( CollectionTransaction::class, OrderMedicine::class );
    }

    public function employee()
    {
    	return $this->belongsTo(Employee::class);
    }

    public function account()
    {
    	return $this->belongsTo(Account::class);
    }

    public function deliverTransaction()
    {
        return $this->hasOne(DeliverTransaction::class)
                    ->withDefault([
                        'delivery_date' => null,
                    ]);
    }

    // RETURNS
    public function returnedOrderMedicines()
    {
        return $this->hasMany(ReturnedOrderMedicine::class);
    }

    // public function orderBatchNo_viaOrderMedicine()
    // {
    //  return $this->hasOneThrough( OrderBatchNo::class, OrderMedicine::class );
    // }

    /**
     * List of statuses.
     *
     * Pending, Approved, Canceled, Delivered.
     *
     * Not Paid, Partially Paid, Fully Paid.
     */

    // wherePendingOrders
    public function scopeWherePendingOrders($query)
    {
        return $query/*->with(['account'])*/
                    ->orderBy('id', 'desc')
                    ->whereIn('status', ['Pending']);
    }

    // whereTrackOrders
    public function scopeWhereTrackOrders($query)
    {
        return $query/*->with(['account'])*/
                    ->whereIn('status', ['Pending', 'Approved', 'Canceled', 'Delivered'])
                    ->orderBy('id', 'desc');
    }

    // whereSales
    public function scopeWhereSales($query)
    {
        return $query->whereIn('status', ['Delivered', 'Partially Paid', 'Fully Paid']);
    }

    // whereBills
    public function scopeWhereBills($query)
    {
        return $query->whereIn('status', ['Delivered', 'Partially Paid']);
    }

    // with all the details
    // withDetails
    public function scopeWithDetails($query)
    {
        return $query->withReceiptNo()
                    ->withAccount()
                    ->withDeliveryDate()
                    ->withDelivery()
                    ->withTotalCost()
                    ;
    }

    // withReceiptNo
    public function scopeWithReceiptNo($query)
    {
        return $query->addSubSelect('receipt_no', function($query) {
            return $query->select('receipt_no')
                    ->from('deliver_transactions')
                    ->whereColumn('order_transactions.id', 'order_transaction_id')
                    ->limit(1);
        });
    }

    // withDeliveryDate
    // public function scopeWithDeliveryDate($query)
    // {
    //     return $query->addSelect(['delivery_date' => DeliverTransaction::select('delivery_date')
    //                                             ->whereColumn('order_transaction_id', 'order_transactions.id')
    //                                             ->limit(1),
    //                             'delivery_date_id' => DeliverTransaction::select('id')
    //                                             ->whereColumn('order_transaction_id', 'order_transactions.id')
    //                                             ->limit(1)]);
    // }

    // withDeliveryDate
    public function scopeWithDeliveryDate($query)
    {
        if($this->has('deliverTransaction'))
            return $query->addSubSelect('delivery_date', function($query) {
                return $query->selectRaw('delivery_date')
                        ->from('deliver_transactions')
                        ->whereColumn('order_transactions.id', 'order_transaction_id')
                        ->limit(1);
            });

        return $query;
    }


    // withDeliverTransactions - details
    public function scopeWithDelivery($query)
    {
        return $query->addSubSelect('order_transaction_id', function($query) {
            return $query->select('id')
                    ->from('deliver_transactions')
                    ->whereColumn('order_transaction_id', 'order_transactions.id')
                    ->limit(1);
        })->with('deliverTransaction');
    }

    // withAccount - details
    public function scopeWithAccount($query)
    {
        return $query->addSubSelect('account_id', function($query) {
            return $query->select('id')
                    ->from('accounts')
                    ->whereColumn('account_id', 'accounts.id')
                    ->limit(1);
        })->with('account');
    }

    // withAccount - details
    public function scopeWithEmployee($query)
    {
        return $query->addSubSelect('employee_id', function($query) {
            return $query->select('id')
                    ->from('employees')
                    ->whereColumn('employee_id', 'employees.id')
                    ->limit(1);
        })->with('employee');
    }

    // withTotalCost
    public function scopeWithTotalCost($query)
    {
        return $query->addSubSelect('total_cost', function($query){
            return $query->selectRaw('sum(order_medicines.total_cost) as total_cost')
                        ->fromSub( OrderMedicine::withTotalCost(), 'order_medicines')
                        ->whereColumn('order_transactions.id', 'order_medicines.order_transaction_id')
                        ->limit(1);
        });
    }
    // withTotalBill
    public function scopeWithTotalBill($query)
    {
        return $query->addSubSelect('total_bill', function($query){
            return $query->selectRaw('sum(order_medicines.payable_cost) as total_bill')
                        ->fromSub( OrderMedicine::withPayables(), 'order_medicines')
                        ->whereColumn('order_transactions.id', 'order_medicines.order_transaction_id')
                        ->limit(1);
        });
    }
    // withTotalPaidAmount
    public function scopeWithTotalPaidAmount($query)
    {
        return $query->addSubSelect('total_paid_amount', function($query){
            return $query->selectRaw('sum(order_medicines.total_paid_amount) as total_paid_amount')
                        ->fromSub( OrderMedicine::withPaid() , 'order_medicines')
                        ->whereColumn('order_transactions.id', 'order_medicines.order_transaction_id')
                        ->limit(1);
        });
    }

    /**
     * CHARTS
     *
     * This is the scopes for charts. Use this to send quick data in charts.
     */
    // scope only for charts

    public function scopeForCharts($query)
    {
        return $query->addSelect([
                    'products.*',
                ])
                ->joinSub( OrderMedicine::withDetails(), 'order_medicines', 'order_medicines.order_transaction_id', '=', 'order_transactions.id' )
                ->joinSub( Product::query(), 'products', 'products.id', '=', 'order_medicines.product_id' )
                ->groupBy('order_medicines.product_id');
    }

    // sales per product
    public function scopeGetSalesPerProduct($query)
    {
        return $query->forCharts()
                    ->addSelect([
                        // total sales quantity
                        DB::raw('sum(order_medicines.total_quantity) as total_sales_quantity'),
                    ]);
    }

    // collections per product
    public function scopeGetCollectionsPerProduct($query)
    {
        return $query->forCharts()
                    ->addSelect([
                        // total collections quantity
                         DB::raw('sum(order_medicines.total_paid_quantity) as total_collections_quantity'),
                    ]);
    }

    /**
     * CUSTOM LOGICS
     *
     */

    // track the status
    public function trackStatus()
    {
        // if total bill is 0 and not null
        if( !is_null($this->withTotalBill()->find($this->id)['total_bill']) &&
            $this->withTotalBill()->find($this->id)['total_bill'] <= 0 )
        {
            $this->update(['status' => 'Fully Paid']);
        }
        else if( !is_null($this->withTotalPaidAmount()->find($this->id)['total_paid_amount']) &&
                    $this->withTotalPaidAmount()->find($this->id)['total_paid_amount'] > 0 )
        {
            $this->update(['status' => 'Partially Paid']);
        }
    }

    public function track_excessPayment()
    {
        // if( $this->withDetails()-> )
    }

    // GET THE ORDERED MEDICINES
    public function showOrderMedicine()
    {
        return DataTables::of( $this->orderMedicine()
                                    ->withDetails() )
                // add columns
                ->addColumn('product_img', function($orderMedicine){
                    return '<div align="center">
                                <img src="'.$orderMedicine->product['product_img'].'" class="img-thumbnail image-50">
                            </div>';
                })
                ->addColumn('product_name', function($orderMedicine){
                    return '<div>
                                <h5 class="text-darker">'.$orderMedicine->product['product_name'].'</h5>
                                <h6 class="text-muted">'.$orderMedicine->product['brand_name'].'</h6>
                                <h6 class="text-muted">'.$orderMedicine->pesoFormat($orderMedicine['unit_price']).'</h6>
                            </div>';
                })
                ->addColumn('batch_nos', function($orderMedicine){
                    $batchNos = '';
                    foreach( $orderMedicine->orderBatchNo as $orderBatchNo ) {

                        $batchNos .= html()->span()->class('text-darker font-weight-bold font-12 ')
                                        ->html(
                                            html()->span()->class('')
                                                ->text( $orderBatchNo->batchNo['batch_no'])
                                            .' - '.
                                            html()->span()->class('text-primary')
                                                ->text( $orderBatchNo->quantityFormat($orderBatchNo['quantity']) )
                                            .' <br> '
                                        );
                    }
                    // return batch numbers...
                    return $batchNos;
                })
                ->addColumn('quantity_and_free', function( $orderMedicine ){
                    return html()->span()->class('font-weight-bold')
                                ->html(
                                    $orderMedicine->withComma( $orderMedicine['quantity_wo_free'] )
                                    .' + '.
                                    $orderMedicine->quantityFormat( $orderMedicine['free'] )
                                );
                })
                ->addColumn('action', function( $orderMedicine ){
                    return '
                        <div class="d-flex">
                            <button class="btn btn-sm btn-warning waves-effect wave-light mx-2" data-toggle="modal" data-target="#edit-order-batch-no-modal" onclick="details('.$orderMedicine['id'].')">
                                <i class="ti-write"></i>
                            </button>
                        </div>';
                })
                ->addColumn('total_cost_format', function($orderMedicine){
                    return '<span class="font-weight-bold text-primary">'.$orderMedicine->pesoFormat( $orderMedicine['total_cost'] ).'</span>';
                })
                // raw columns
                ->rawColumns(['product_img', 'product_name', 'batch_nos', 'quantity_and_free', 'total_cost_format', 'action',])
                //convert to json
                ->toJson();
    }

    // track if total bill is negative
    public function trackTotalBill_ifNegative($total_bill)
    {
        if($total_bill < 0){
            // 
        }
    }

    // put excess payment
    public function getExcessPaymentAttribute()
    {
        $this_orderTransaction = $this
            ->whereSales()
            ->withDetails()
            ->find( $this->id );

        // condition
        if( $this_orderTransaction['total_bill'] < 0 )
            return $this_orderTransaction['total_bill'] * (-1);

        return 0;
    }

}
