<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeProduct extends Pivot
{
    public $incrementing = true;

    protected $casts = [
    	'active' => 'boolean',
    ];

    // protected $appends = [ 'paid_amount_format' ];
}
