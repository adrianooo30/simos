<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

	use CustomTraits\FormatsTrait;

    protected $guarded = [];

    // this is only to get the notifications using model
}
