<?php

namespace App\CustomClasses\NotificationFilter;

class Read extends Filter
{
	//
	// must be in child class - i think so...
	public function applyFilter($builder)
	{		
		return $builder->readNotifications();		
	}
}