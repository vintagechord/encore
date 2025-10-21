<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    protected $fillable = [
        'category',
        'title',
        'role',
        'event_name',
        'event_date',
        'location',
        'thumbnail_path',
        'summary',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'is_active'    => 'boolean',
        'display_order'=> 'integer',
    ];
}
