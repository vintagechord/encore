<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareTokenEvent extends Model
{
    protected $table = 'share_token_events';

    protected $fillable = [
        'recommendation_set_id',
        'token',
        'event_type', // 'issued' | 'rotated' | 'revoked'
    ];

    public $timestamps = true; // created_at만 쓰면 됨(updated_at 자동이지만 무시)
}
