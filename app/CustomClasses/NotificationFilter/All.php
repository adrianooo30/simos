<?php

namespace App\CustomClasses\NotificationFilter;

class All extends Filter
{
	//
	// must be in child class - i think so...
	public function applyFilter($builder)
	{
		return $builder->notifications();		
	}
}