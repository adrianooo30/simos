<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Traits\HasRoles;

class Position extends Model
{
	use HasRoles;

    protected $guard_name = 'web';

    protected $guarded = [];

    public function employee()
    {
    	return $this->hasMany(Employee::class);
    }

    public function modules()
    {
    	return $this->hasMany(Module::class);
    }
}
