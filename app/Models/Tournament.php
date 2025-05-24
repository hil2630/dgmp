<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'name',
        'type', // 'main' or 'on_day'
        'date',
        'prize_pool',
        'description',
        'status', // 'upcoming', 'active', 'completed'
        'distribution', // JSON field for prize distribution
        'winner_team_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'prize_pool' => 'integer',
        'distribution' => 'array',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function winners()
    {
        return $this->hasMany(TournamentWinner::class);
    }

    public function winnerTeam()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
}
