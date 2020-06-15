<?php

namespace App\CustomClasses;

class CustomCollections
{
	public static function instance()
	{
		return new self;
	}

	public function naku()
	{
		return 'hello po';
	}
}