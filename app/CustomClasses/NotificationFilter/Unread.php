<?php

namespace App\CustomClasses\NotificationFilter;

class Unread extends Filter
{
	//
	// must be in child class - i think so...
	public function applyFilter($builder)
	{		
		return $builder->unreadNotifications();
	}
}