<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountMisc extends Model
{
	use CustomTraits\FormatsTrait;

    protected $guarded = [];

    public function account()
    {
    	return $this->belongsTo( Account::class );
    }
}
