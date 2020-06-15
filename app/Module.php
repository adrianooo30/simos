<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Spatie\Permission\Models\Permission;

class Module extends Model
{
    protected $guarded = [];

    public function permissions()
    {
    	return $this->hasMany(Permission::class);
    }
}
