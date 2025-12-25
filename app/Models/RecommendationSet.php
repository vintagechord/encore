<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecommendationSet extends Model
{
    protected $table = 'recommendation_sets';

    protected $fillable = [
        'intake_request_id',
        'items',        // 레거시 JSON(필요하면 유지)
        'options',      // 설정 JSON
        'label',
        'total_cost',
        'public_token',
        'sent_at',
        'notes',
    ];

    protected $casts = [
        'items'      => 'array',     // 레거시 JSON
        'options'    => 'array',
        'sent_at'    => 'datetime',
        'total_cost' => 'integer',
    ];

    /** 🔁 관계 이름을 items→entries 로 변경해 충돌 제거 */
    public function entries(): HasMany
    {
        return $this->hasMany(RecommendationSetItem::class, 'recommendation_set_id');
    }

    /** (선택) 과거 코드 호환용 별칭: 새 코드는 사용하지 말고 점차 제거하세요 */
    public function itemsRelation(): HasMany
    {
        return $this->entries();
    }

    public function intake()
    {
        return $this->belongsTo(IntakeRequest::class, 'intake_request_id');
    }

    /**
     * Backwards-compat alias used by some controllers/views.
     */
    public function intakeRequest()
    {
        return $this->intake();
    }

    /**
     * Build public URL for this recommendation set if token exists.
     */
    public function publicUrl(): string
    {
        return url('/r/' . $this->public_token);
    }
}
