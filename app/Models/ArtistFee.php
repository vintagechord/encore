<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistFee extends Model
{
    protected $fillable = [
        'artist_id',
        'currency',
        'min_fee',
        'max_fee',
        'unit',
        'region_code',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
