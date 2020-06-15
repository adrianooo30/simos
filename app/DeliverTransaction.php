<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliverTransaction extends Model
{
    protected $guarded = [];

    public function orderTransaction()
   	{
   		return $this->belongsTo(OrderTransaction::class);
   	}

   	public function employee()
   	{
   		return $this->belongsTo(Employee::class);
   	}

}
