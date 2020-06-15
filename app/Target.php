<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'start_date' => 'datetime',
    	'end_date' => 'datetime',
    ];

    public function employee()
    {
    	return $this->belongsTo(Employee::class);
    }
}
