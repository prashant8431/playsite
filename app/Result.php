<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $guarded = ['id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
