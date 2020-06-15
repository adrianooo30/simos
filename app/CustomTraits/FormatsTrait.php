<?php

namespace App\CustomTraits;

trait FormatsTrait
{
	public function pesoFormat($value)
	{
		return '&#8369; '.number_format($value, 2);
	}

	public function quantityFormat($value)
	{
		return $this->withComma($value).' pcs.';
	}

	public function withComma($value)
	{
		return number_format($value);
	}
}