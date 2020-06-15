<?php

namespace App\CustomClasses;

class FormatsTrait
{
	public static function pesoFormat($value)
	{
		return '&#8369; '.number_format($value, 2);
	}

	public static function quantityFormat($value)
	{
		return number_format($value).' pcs.';
	}
}