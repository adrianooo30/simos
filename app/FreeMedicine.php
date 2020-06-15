<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeMedicine extends Model
{
    protected $guarded = [];

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function account()
    {
    	return $this->belongsTo(Account::class);
    }

}
