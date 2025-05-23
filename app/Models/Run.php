<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Jetstream\Jetstream;

class Run extends Model
{
    use HasFactory;

    public const DUNGEONS = [
        'The MOTHERLODE!!',
        'Darkflame Cleft',
        'Theater of Pain',
        'The Rookery',
        'Cinderbrew Meadery',
        'Operation: Floodgate',
        'Mechagon Workshop',
        'Priory of the Sacred Flame',
    ];

    protected $fillable = [
        'team_id',
        'season_id',
        'dungeon_name',
        'key_level',
        'time_taken_seconds',
        'warcraft_log_url',
        'status', // 'completed', 'failed', 'in_progress'
        'approval_status', // 'pending', 'approved', 'denied'
    ];

    protected $casts = [
        'time_taken_seconds' => 'integer',
        'key_level' => 'integer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function getFormattedTimeAttribute(): string
    {
        $minutes = floor($this->time_taken_seconds / 60);
        $seconds = $this->time_taken_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    public function getIsInProgressAttribute(): bool
    {
        return $this->status === 'in_progress';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function getIsDeniedAttribute(): bool
    {
        return $this->approval_status === 'denied';
    }
}