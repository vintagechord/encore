<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistUnavailability extends Model
{
    protected $fillable = ['artist_id', 'starts_on', 'ends_on', 'reason'];
    protected $casts = [
        'starts_on' => 'date',
        'ends_on'   => 'date',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
