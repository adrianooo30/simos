<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnedOrderMedicine extends Model
{
    protected $guarded = [];

    public function orderTransaction()
    {
    	return $this->belongsTo(OrderTransaction::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function returnedOrderBatchNos()
    {
    	return $this->hasMany(ReturnedOrderBatchNo::class);
    }

    public function replace()
    {
        return $this->hasOne(Replace::class);
    }

    /**
     * with total returned quantity
     */
    public function scopeWithQuantity($query)
    {
        return $query->addSubSelect('returned_quantity', function($query){
            return $query->selectRaw('sum(returned_order_batch_nos.quantity) as returned_quantity')
                        ->from('returned_order_batch_nos')
                        ->whereColumn('returned_order_medicines.id', 'returned_order_batch_nos.returned_order_medicine_id')
                        ->limit(1);
        });
    }

    //
    public function getOrderMedicine()
    {
        return $this->orderTransaction()
                    ->first()
                    ->orderMedicine()
                    ->whereProductId( $this->product_id )
                    ->withTotalQuantity();
                    // ->first();
    }
}
