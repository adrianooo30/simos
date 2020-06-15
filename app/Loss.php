<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loss extends Model
{
    protected $guarded = [];

    public function batchNo()
    {
    	return $this->belongsTo(BatchNo::class);
    }
}
