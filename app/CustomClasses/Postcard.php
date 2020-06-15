<?php

namespace App\CustomClasses;

class Postcard
{
	public static function resolveFacade( $name )
	{
		return app()[ $name ];
	}

	public static function __callStatic($method, $attributes)
	{
		return ( self::resolveFacade( 'PostcardSendingService' ) )
			->$method( ...$attributes );
	}
}