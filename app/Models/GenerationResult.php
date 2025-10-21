<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenerationResult extends Model
{
    protected $fillable = [
        'generation_request_id',
        'option_index',
        'artist_ids',
        'subtotal',
        'score_breakdown',
    ];

    protected $casts = [
        'artist_ids'      => 'array',
        'score_breakdown' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(GenerationRequest::class, 'generation_request_id');
    }
}
