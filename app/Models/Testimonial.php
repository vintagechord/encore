<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'kind',
        'title',
        'body',
        'meta',
        'rating',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
