<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'role',
        'rio_score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
