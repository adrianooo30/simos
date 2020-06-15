<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

use DB;

class OrderMedicine extends Model
{
    use CustomTraits\FormatsTrait;

    protected $guarded = [];

    protected $appends = [
        // 'total_quantity',
        // 'total_quantity_format',
        // 'total_cost',
        // 'total_cost_format',
        // 'unit_price_format',

        // 'payables_quantity',
        // 'payables_quantity_format',
        // 'payables_cost',
        // 'payables_cost_format',
    ];

    protected static function booted()
    {
        // static::addGlobalScope('total_quantity', function(Builder $builder){
        //     $builder->withTotalQuantity();
        // });

        // static::addGlobalScope('total_cost', function(Builder $builder){
        //     $builder->withTotalCost();
        // });
    }

    public function orderTransaction()
    {
    	return $this->belongsTo(OrderTransaction::class);
    }

    public function orderBatchNo()
    {
    	return $this->hasMany(OrderBatchNo::class);
    }

    // order medicine to collections
    
    public function collectionTransactions()
    {
        return $this->belongsToMany(CollectionTransaction::class, 'collection_transaction_order_medicine')
                    ->using( CollectionTransactionOrderMedicine::class )
                    ->withPivot(['paid_quantity', 'paid_amount'])
                    ->as('paid')
                    ->withTimestamps()
                    // ->withDefault([
                    //     'paid_quantity' => 0,
                    //     'paid_amount' => 0,
                    // ])
                    ;
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    /**
     * Section for SCOPES.
     *
     * @return query
     */

    // whereInDeliveredDate
    public function scopeWhereInDeliveredDate($query, $from_date, $to_date)
    {
        return $query->whereHas('orderTransaction', function($query) use ($from_date, $to_date){
            return $query->withDeliveryDate()
                    ->whereBetween('delivery_date', [$from_date, $to_date]);
        });
    }

    // whereInCollectionDate
    public function scopeWhereInCollectionDate($query, $from_date, $to_date)
    {
        return $query->whereHas('collectionTransactions', function($query) use ($from_date, $to_date){
            return $query->whereBetween('collection_date', [$from_date, $to_date]);
        });
    }

    public function scopeWherePayables($query)
    {
        return $query
                    // ->withPayables()
                    ->whereNull('payable_quantity')
                    ->where('payable_quantity', '>', 0)
                    ;
    }


    // withDetails - totalCost, totalPaid, totalPayables
    public function scopeWithDetails($query)
    {
        return $query
                    ->withTotalQuantity()
                    ->withQuantityWOFree()
                    ->withTotalCost()
                    ->withPaid()
                    ->withPayables()
                    ;
    }

    // withTotalQuantity - including free quantity
    public function scopeWithTotalQuantity($query)
    {
        return $query->addSubSelect('total_quantity', function($query){
            return $query->selectRaw('sum(order_batch_nos.quantity) as total_quantity')
                        ->from('order_batch_nos')
                        ->whereColumn('order_medicines.id', 'order_batch_nos.order_medicine_id')
                        ->limit(1);
        });
    }

    // withQuantityWOFree
    public function scopeWithQuantityWOFree($query)
    {
        return $query->addSubSelect('quantity_wo_free', function($query){
            return $query->selectRaw('(sum(order_batch_nos.quantity) - order_medicines.free) as total_quantity')
                        ->from('order_batch_nos')
                        ->whereColumn('order_medicines.id', 'order_batch_nos.order_medicine_id')
                        ->limit(1);
        });
    }

    // withTotalCost - without the free
    public function scopeWithTotalCost($query)
    {
        return $query->addSubSelect('total_cost', function($query){
            return $query->selectRaw('(sum(order_batch_nos.quantity) - order_medicines.free) * order_medicines.unit_price as total_cost')
                        ->from('order_batch_nos')
                        ->whereColumn('order_medicines.id', 'order_batch_nos.order_medicine_id')
                        ->limit(1);
        });
    }

    // withPaid
    public function scopeWithPaid($query)
    {
        return $query->addSubSelect('total_paid_quantity', function($query){
                        // return query
                        return $query->selectRaw('sum(collection_transaction_order_medicine.paid_quantity) as total_paid_quantity')
                                    ->from('collection_transaction_order_medicine')
                                    ->whereColumn('order_medicines.id', 'collection_transaction_order_medicine.order_medicine_id')
                                    ->limit(1);
                    })
                    ->addSubSelect('total_paid_amount', function($query){
                        // return query
                        return $query->selectRaw('sum(collection_transaction_order_medicine.paid_quantity) * order_medicines.unit_price as total_paid_amount')
                                    ->from('collection_transaction_order_medicine')
                                    ->whereColumn('order_medicines.id', 'collection_transaction_order_medicine.order_medicine_id')
                                    ->limit(1);
                    });
    }

    // withPayables
    public function scopeWithPayables($query)
    {
        return $query->addSubSelect('payable_quantity', function($query){
                        // return query
                        return $query->selectRaw('(order_medicines_misc.quantity_wo_free - order_medicines_misc.total_paid_quantity) as payable_quantity')
                                    ->fromSub( OrderMedicine::withQuantityWOFree()
                                                            ->withPaid(), 'order_medicines_misc')
                                    ->whereColumn('order_medicines.id', 'order_medicines_misc.id')
                                    ->limit(1);
                    })
                    ->addSubSelect('payable_cost', function($query){
                        // return query
                        return $query->selectRaw('(order_medicines_misc.total_cost - order_medicines_misc.total_paid_amount) as payable_cost')
                                    ->fromSub( OrderMedicine::withTotalCost()
                                                            ->withPaid(), 'order_medicines_misc')
                                    ->whereColumn('order_medicines.id', 'order_medicines_misc.id')
                                    ->limit(1);
                    });
    }

    // withCollectionDate
    public function scopeWithCollectionDate($query)
    {
        return $query->addSubSelect('collection_date', function($query){
                        // return query
                        return $query->select([
                                'collection_transactions.collection_date',
                            ])
                            ->from('collection_transactions')
                            ->join('collection_transaction_order_medicine', 'order_medicines.id', '=', 'collection_transaction_order_medicine.order_medicine_id')
                            ->whereColumn('order_medicines.id', 'collection_transaction_order_medicine.order_medicine_id')
                            ->limit(1);
                    });
    }

    /**
     * Section for ACCESORS.
     *
     * @return query
     */

    // bakanteng lote

    /**
     * Section for CUSTOM LOGICS.
     *
     * @return query
     */
    public function getReturnedMedicines()
    {
        return $this->orderTransaction()
                    ->first()
                    ->returnedOrderMedicines()
                    ->whereProductId( $this->product_id )
                    ->withQuantity();
                    // ->first();
    }

}
