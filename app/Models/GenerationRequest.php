<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GenerationRequest extends Model
{
    protected $fillable = [
        'intake_request_id',
        'budget_total',
        'event_start',
        'event_end',
        'genre_counts',
        'locked_artist_ids',
        'excluded_artist_ids',
        'seed',
        'option_count',
        'client_ip',
    ];

    protected $casts = [
        'event_start' => 'date',
        'event_end'   => 'date',
        'genre_counts' => 'array',
        'locked_artist_ids' => 'array',
        'excluded_artist_ids' => 'array',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(GenerationResult::class, 'generation_request_id');
    }
}
