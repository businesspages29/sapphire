<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    protected $fillable = [
        'id',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}