<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'guild',
        'is_anonymous',
    ];

    protected $casts = [
        'amount' => 'integer',
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
