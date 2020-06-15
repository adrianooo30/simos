<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replace extends Model
{
    protected $guarded = [];

    public function returnedOrderMedicine()
    {
    	return $this->belongsTo( ReturnedOrderMedicine::class );
    }
}
