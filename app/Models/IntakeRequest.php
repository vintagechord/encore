<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IntakeRequest extends Model
{
    protected $table = 'intake_requests';

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone',
        'org_name',
        'biz_cert_path',

        // 단일 날짜(레거시 입력 대응)
        'event_date',
        'date_flexible',

        // 행사 기간(단일 일정이면 start=end)
        'event_start',
        'event_end',

        'city',
        'venue_type',
        'indoor_outdoor',
        'audience_size',
        'currency',

        // 예산 범위
        'budget_min',
        'budget_max',

        // 고객 선호/설정
        'genres',
        'moods',
        'audience_age',
        'performance_type',
        'set_duration',
        'sets_count',
        'equipment_available',
        'requested_artist_name',

        // 레거시 단일 카테고리(옵션)
        'category',

        // ✅ 신규: 복수 카테고리/세부 선택
        'performance_categories',   // ["music","dance","mc"]
        'music_genres',             // ["pop","rock",...]
        'dance_genres',             // ["kpop","street",...]
        'mc_roles',                 // ["announcer","comedian",...]

        // ✅ 장르별 인원 수 요청 (예: {"pop":2,"dance":1})
        'genre_counts',

        'notes',
        'status',
    ];

    protected $casts = [
        // 기본
        'event_date'           => 'date',
        'date_flexible'        => 'boolean',
        'genres'               => 'array',
        'moods'                => 'array',
        'equipment_available'  => 'array',

        // 일정
        'event_start'          => 'date',
        'event_end'            => 'date',

        // 수량 맵
        'genre_counts'         => 'array',

        // 복수 선택 필드
        'performance_categories' => 'array',
        'music_genres'           => 'array',
        'dance_genres'           => 'array',
        'mc_roles'               => 'array',
    ];

    // --------------------------
    // Relationships
    // --------------------------

    /** 이 문의에 속한 추천셋들 */
    public function recommendationSets(): HasMany
    {
        return $this->hasMany(RecommendationSet::class, 'intake_request_id');
    }

    /** 최신 추천셋(있다면) */
    public function latestSet(): HasOne
    {
        return $this->hasOne(RecommendationSet::class, 'intake_request_id')->latestOfMany();
    }

    // --------------------------
    // Query Scopes
    // --------------------------

    /**
     * 목록용 최신 플래그들(최근 sent_at, token, set id) 서브쿼리로 붙이기
     */
    public function scopeWithLatestFlags($query)
    {
        return $query->addSelect([
            'latest_sent_at' => RecommendationSet::select('sent_at')
                ->whereColumn('intake_request_id', 'intake_requests.id')
                ->orderByDesc('id')->limit(1),

            'latest_token' => RecommendationSet::select('public_token')
                ->whereColumn('intake_request_id', 'intake_requests.id')
                ->orderByDesc('id')->limit(1),

            'latest_set_id' => RecommendationSet::select('id')
                ->whereColumn('intake_request_id', 'intake_requests.id')
                ->orderByDesc('id')->limit(1),
        ]);
    }
}
