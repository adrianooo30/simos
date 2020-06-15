<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $guarded = [];

    protected $appends = [ 'deal_name' ];

    // product
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    // accounts
    public function accounts()
    {
    	return $this->belongsToMany(Account::class)->withTimestamps();
    }

    // ACCESSORS
    public function getDealNameAttribute()
    {
        return "{$this->attributes['buy']} + {$this->attributes['take']}";
    }
}
