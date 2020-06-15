<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CollectionTransactionOrderMedicine extends Pivot
{
    public $incrementing = true;

    use CustomTraits\FormatsTrait;

    // protected $appends = [ 'paid_quantity_format', 'paid_amount_format', ];

    // public function getPaidQuantityFormatAttribute()
    // {
    // 	return number_format($this->paid_quantity).' pcs.';
    // }

    // public function getPaidAmountFormatAttribute()
    // {
    // 	return '&#8369; '.number_format($this->paid_amount, 2);
    // }
}
