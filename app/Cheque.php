<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
   	protected $guarded = [];

   	public function collection()
   	{
   		return $this->belongsTo(CollectionTransaction::class);
   	}
    
}
