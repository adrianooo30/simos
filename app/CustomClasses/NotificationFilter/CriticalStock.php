<?php

namespace App\CustomClasses\NotificationFilter;

class CriticalStock extends Filter
{
	// must be in child class - i think so...
	public function applyFilter($builder)
	{		
		return $builder->where('type', 'LIKE', '%'.Str::studly( request('notification_type') ).'%');		
	}
}