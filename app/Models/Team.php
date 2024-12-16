<?php

namespace App\Models;

use App\Enums\GameResult;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'round',
        'result',
    ];

    protected $casts = [
        'result' => GameResult::class,
    ];

    public function gameMatch()
    {
        return $this->belongsTo(GameMatch::class);
    }

    public function scopeWinner($query, $round = 1)
    {
        return $query->where('round', $round)->where('result', GameResult::WIN);
    }

    public function scopeLoser($query, $round = 1)
    {
        return $query->where('round', $round)->where('result', GameResult::LOSE);
    }
}
