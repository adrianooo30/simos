<?php

namespace App\CustomClasses;

class PostcardSendingService
{

	public $attributes;

	public function __construct($attributes)
	{
		$this->attributes = $attributes;
	}

	public function hellow( $name, $from )
	{
		return dd( 'Hellow '. $name. ' from ' . $from );
	}
}