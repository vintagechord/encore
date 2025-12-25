<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    // ⚠️ fillable에는 실제 DB에 있는 컬럼만 남기는 게 안전합니다.
    protected $fillable = [
        // 표준/레거시
        'name','genre','fee_min','fee_max','meta',
        // 신규 스키마 필드
        'discipline_id','stage_name','legal_name','fame_score','external_links','bio','active',
        // 이미지 관련
        'image_path','image_url',
        // 레거시 보조
        'min_fee','max_fee','genres','moods','formats','home_city','notes',
    ];

    protected $casts = [
        // 실제로 있는 컬럼만 남기세요
        'genres'  => 'array',
        'moods'   => 'array',
        'formats' => 'array',
        'active'  => 'boolean',
        'meta'    => 'array',
        'external_links' => 'array',
    ];

    /* =========================================================
     |  Relationships
     * =======================================================*/

    /**
     * 이 아티스트가 포함된 추천셋들 (pivot: artist_recommendation_set)
     * 기본적으로 pivot.rank 오름차순.
     */
    public function recommendationSets(): BelongsToMany
    {
        return $this->belongsToMany(
            RecommendationSet::class,
            'artist_recommendation_set',
            'artist_id',
            'recommendation_set_id'
        )
            ->withPivot(['rank', 'score', 'reason'])
            ->withTimestamps()
            ->orderByPivot('rank');
    }

    /**
     * 추천셋 아이템(신규 스키마: recommendation_set_items)이 있는 경우에 사용.
     * (없으면 호출하지 않으면 됨)
     */
    public function recommendationSetItems(): HasMany
    {
        return $this->hasMany(RecommendationSetItem::class);
    }

    /**
     * 일정 불가 기간
     */
    public function unavailabilities(): HasMany
    {
        return $this->hasMany(ArtistUnavailability::class);
    }

    /**
     * 분야(예: 음악, MC, 댄스) — 새 스키마용
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * 장르 다대다(artist_genre) — 새 스키마용
     */
    public function genresRelation(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'artist_genre');
    }

    /**
     * 태그 다대다(artist_tag) — 새 스키마용
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'artist_tag');
    }

    /**
     * 찜한 사용자 목록
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'artist_favorites')->withTimestamps();
    }

    /**
     * 섭외비(다중 요율) — 새 스키마용
     */
    public function fees(): HasMany
    {
        return $this->hasMany(ArtistFee::class);
    }

    /* =========================================================
     |  Accessors (표준 필드로의 폴백)
     * =======================================================*/

    // name 없으면 stage_name 반환
    public function getNameAttribute($value)
    {
        if (!is_null($value)) return $value;
        $alt = $this->getAttribute('stage_name');
        return $alt ?? null;
    }

    // genre 없으면 genres(배열) 반환
    public function getGenreAttribute($value)
    {
        if (!is_null($value)) return $value;        // 문자열 또는 배열 그대로
        $plural = $this->getAttribute('genres');    // 배열일 가능성
        return $plural ?? null;
    }

    // fee_min 없으면 min_fee 사용
    public function getFeeMinAttribute($value)
    {
        if (!is_null($value)) return (int) $value;
        $alt = $this->getAttribute('min_fee');
        return is_null($alt) ? null : (int) $alt;
    }

    // fee_max 없으면 max_fee 사용
    public function getFeeMaxAttribute($value)
    {
        if (!is_null($value)) return (int) $value;
        $alt = $this->getAttribute('max_fee');
        return is_null($alt) ? null : (int) $alt;
    }

    /**
     * 읽기 편한 요율 범위(신규 스키마 fees가 있으면 우선 사용, 없으면 레거시 컬럼 폴백).
     * $artist->fee_range 형식으로 접근: ['min'=>..., 'max'=>..., 'currency'=>'KRW', 'unit'=>'appearance']
     */
    public function getFeeRangeAttribute(): ?array
    {
        try {
            // 활성 요율 우선
            $fee = $this->fees()
                ->where('is_active', true)
                ->orderByRaw('min_fee is null') // null을 뒤로
                ->orderBy('min_fee')
                ->first();
        } catch (\Throwable $e) {
            $fee = null; // fees 테이블이 없거나 관계를 안 쓰는 경우
        }

        if ($fee) {
            return [
                'min'      => isset($fee->min_fee) ? (int)$fee->min_fee : null,
                'max'      => isset($fee->max_fee) ? (int)$fee->max_fee : null,
                'currency' => $fee->currency ?? 'KRW',
                'unit'     => $fee->unit ?? 'appearance',
            ];
        }

        $min = $this->fee_min ?? $this->min_fee;
        $max = $this->fee_max ?? $this->max_fee;

        if (is_null($min) && is_null($max)) {
            return null;
        }

        return [
            'min'      => is_null($min) ? null : (int)$min,
            'max'      => is_null($max) ? null : (int)$max,
            'currency' => 'KRW',
            'unit'     => 'appearance',
        ];
    }

    /* =========================================================
     |  Scopes (관리자 검색/필터)
     * =======================================================*/

    /**
     * 관리자 목록/검색용 필터 스코프.
     * $query->filter([
     *   'q' => '검색어',
     *   'discipline_id' => 1,
     *   'min_fame' => 30,
     *   'max_fame' => 80,
     *   'genre_ids' => [1,2],
     *   'tag_ids' => [3,4],
     *   'is_active' => true,
     * ])
     */
    public function scopeFilter($query, array $f)
    {
        // 텍스트 검색: name/stage_name 내 부분일치
        if (!empty($f['q'])) {
            $q = $f['q'];
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('stage_name', 'like', "%{$q}%")
                    ->orWhere('legal_name', 'like', "%{$q}%");
            });
        }

        // 분야
        if (!empty($f['discipline_id'])) {
            $query->where('discipline_id', $f['discipline_id']);
        }

        // 유명세(스키마에 없으면 무시됨)
        if (isset($f['min_fame'])) $query->where('fame_score', '>=', (int)$f['min_fame']);
        if (isset($f['max_fame'])) $query->where('fame_score', '<=', (int)$f['max_fame']);

        // 장르/태그 (새 스키마 관계 사용)
        if (!empty($f['genre_ids'])) {
            $ids = (array) $f['genre_ids'];
            $query->whereHas('genresRelation', fn($qq) => $qq->whereIn('genres.id', $ids));
        }
        if (!empty($f['tag_ids'])) {
            $ids = (array) $f['tag_ids'];
            $query->whereHas('tags', fn($qq) => $qq->whereIn('tags.id', $ids));
        }

        // 사용 여부
        if (array_key_exists('is_active', $f) && $f['is_active'] !== null) {
            $query->where('active', (bool)$f['is_active']);
        }

        return $query;
    }
}
