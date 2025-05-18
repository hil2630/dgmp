<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Jetstream;

class Bracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'starts_at',
        'time_limit_hours',
        'is_locked',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    public function runs(): HasMany
    {
        return $this->hasMany(Run::class);
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->status !== 'active') {
            return null;
        }

        return now()->diffInSeconds($this->starts_at->addHours($this->time_limit_hours));
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && !$this->is_locked;
    }

    public function teams()
    {
        return $this->belongsToMany(Jetstream::teamModel(), 'bracket_team');
    }
}
