<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $guarded = [];

    protected $appends = [ 'unit_price_format' ];

    public function product()
    {
    	return $this->belongsTo( Product::class );
    }

    public function accounts()
    {
    	return $this->belongsToMany( Account::class );
    }

    /**
     * Section for ACCESORS.
     *
     * @return query
     */
    public function getUnitPriceFormatAttribute()
    {
    	return number_format($this->unit_price, 2).'';
    }
}
