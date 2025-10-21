<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationSetItem extends Model
{
    protected $table = 'recommendation_set_items';

    protected $fillable = [
        'recommendation_set_id',
        'artist_id',
        'rank',
        'fixed',
        'excluded',
        'quoted_min',
        'quoted_max',
        'meta',
    ];

    protected $casts = [
        'fixed' => 'boolean',
        'excluded' => 'boolean',
        'meta' => 'array',
    ];

    public function set()
    {
        return $this->belongsTo(RecommendationSet::class, 'recommendation_set_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
