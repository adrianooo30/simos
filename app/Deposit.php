<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $guarded = [];

    public function collectionTransaction()
    {
    	return $this->belongsTo(CollectionTransaction::class);
    }

    public function employee()
    {
    	return $this->belongsTo(Employee::class);
    }

}
