<?php

namespace App\Models;

use App\Enums\GameResult;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'round_1',
        'round_2',
        'round_3',
        'round_4',
        'result',
    ];

    protected $casts = [
        'result' => GameResult::class,
    ];

    public function gameMatch()
    {
        return $this->belongsTo(GameMatch::class);
    }
}
