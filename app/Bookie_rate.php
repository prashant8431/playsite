<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookie_rate extends Model
{
    protected $guarded = ['id'];

    public function bookie()
    {
        return $this->belongsTo(User::class);
    }
}
