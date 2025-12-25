<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $fillable = [
        'intake_request_id','title','body','amount','currency','status','sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function intake(): BelongsTo
    {
        return $this->belongsTo(IntakeRequest::class, 'intake_request_id');
    }
}

