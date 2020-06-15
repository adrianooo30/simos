<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notified extends Model
{
    protected $guarded = [];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
