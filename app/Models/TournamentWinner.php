<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_id',
        'position', // 1st, 2nd, 3rd
        'prize_amount',
    ];

    protected $casts = [
        'prize_amount' => 'integer',
        'position' => 'integer',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}