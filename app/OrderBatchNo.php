<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBatchNo extends Model
{
	use CustomTraits\FormatsTrait;

    protected $guarded = [];

    public function orderMedicine()
    {
    	return $this->belongsTo(OrderMedicine::class);
    }

    public function batchNo()
    {
    	return $this->belongsTo(BatchNo::class);
    }

}
