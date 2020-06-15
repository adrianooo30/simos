<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnedOrderBatchNo extends Model
{

	use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $guarded = [];

    public function returnedOrderMedicine()
    {
    	return $this->belongsTo( ReturnedOrderMedicine::class );
    }

    public function batchNo()
    {
    	return $this->belongsTo( BatchNo::class );
    }

    // HAS ONE DEEP
    // public function product()
    // {
    // 	return $this->hasOneThrough(Product::class, ReturnedOrderMedicine::class);
    // }
}
