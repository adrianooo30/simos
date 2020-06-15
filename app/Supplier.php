<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    
    public function batchNo()
    {
    	return $this->hasMany(BatchNo::class);
    }

}