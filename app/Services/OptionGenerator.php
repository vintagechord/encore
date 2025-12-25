<?php

namespace App\Services;

use App\Models\Artist;
use App\Models\GenerationRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class OptionGenerator
{
    /**
     * @return array<int, array{artist_ids: int[], subtotal: int, score_breakdown: array}>
     */
    public function generate(GenerationRequest $req): array
    {
        // 1) 파라미터 정리
        $budget = (int) ($req->budget_total ?? 0);
        $genreCounts = collect($req->genre_counts ?? []);
        $locked = collect($req->locked_artist_ids ?? [])->map(fn($v) => (int)$v)->unique()->values();
        $excluded = collect($req->excluded_artist_ids ?? [])->map(fn($v) => (int)$v)->unique()->values();
        $n = max(1, (int) $req->option_count ?: 3);

        // 2) 고정 아티스트 로드 및 예산/쿼터 차감
        $lockedArtists = Artist::whereIn('id', $locked)->get();
        $lockedSubtotal = (int) $lockedArtists->sum(fn($a) => $this->feePoint($a));
        $remainBudget = max(0, $budget - $lockedSubtotal);

        $remainQuota = $genreCounts->map(function ($need, $genre) use ($lockedArtists) {
            $already = $lockedArtists->filter(function ($a) use ($genre) {
                return $this->hasGenre($a, $genre);
            })->count();
            return max(0, ((int)$need) - $already);
        });

        // 3) 후보군: 장르/일정/제외 필터 (일정 필터는 나중 단계에서 상세화)
        $candidates = Artist::query()
            ->with(['genresRelation','discipline'])
            ->when($excluded->isNotEmpty(), fn($q) => $q->whereNotIn('id', $excluded))
            ->get()
            ->filter(function ($a) use ($req) {
                // 일정 불가 제외
                if ($req->event_start && $req->event_end) {
                    $s = $req->event_start->toDateString();
                    $e = $req->event_end->toDateString();
                    $overlap = $a->unavailabilities()->where(function ($w) use ($s, $e) {
                        $w->where('starts_on', '<=', $e)->where('ends_on', '>=', $s);
                    })->exists();
                    if ($overlap) return false;
                }
                return true;
            });

        // 4) 옵션 N개 생성
        $options = [];
        $seedBase = crc32(($req->seed ?: 'encore') . '-' . (string)$req->id);
        $used = collect($lockedArtists->pluck('id')->all());
        for ($k = 0; $k < $n; $k++) {
            $seed = $seedBase + $k;
            $pool = $candidates->reject(fn($a) => $used->contains($a->id));
            $opt = $this->buildOneOption($pool, $remainQuota, $remainBudget, $lockedArtists, $seed);
            $options[] = $opt;
            $used = $used->merge($opt['artist_ids'])->unique();
        }
        return $options;
    }

    protected function buildOneOption(Collection $candidates, Collection $remainQuota, int $budget, Collection $lockedArtists, int $seed): array
    {
        mt_srand($seed);

        $chosen = collect($lockedArtists->pluck('id')->all());
        $subtotal = (int) $lockedArtists->sum(fn($a) => $this->feePoint($a));

        $byGenre = $this->groupByGenreBestEffort($candidates->reject(fn($a) => $chosen->contains($a->id)));

        foreach ($remainQuota as $genre => $need) {
            $pool = collect($byGenre[$genre] ?? []);

            // 비용적합도 + 약간의 랜덤 가중치
            $pool = $pool->map(function ($a) {
                $fee = $this->feePoint($a);
                $score = 1 / max(1, $fee); // 저비용 선호(간단한 휴리스틱)
                return ['a' => $a, 'score' => $score];
            })->sortByDesc('score')->values();

            for ($i = 0; $i < (int)$need; $i++) {
                // 상위 6명 중 랜덤 픽(다양성)
                $top = $pool->take(6)->pluck('a')->all();
                if (empty($top)) break;
                $pick = $top[mt_rand(0, count($top) - 1)];

                $cost = $this->feePoint($pick);
                if ($subtotal + $cost <= $budget) {
                    $chosen->push($pick->id);
                    $subtotal += $cost;
                    // 동일 아티스트 중복 방지
                    $pool = $pool->reject(fn($row) => $row['a']->id === $pick->id)->values();
                } else {
                    // 예산 초과면 다음 저가 후보 시도
                    $pool = $pool->sortBy(fn($row) => $this->feePoint($row['a']))->values();
                    $found = false;
                    foreach ($pool as $row) {
                        $a = $row['a'];
                        $cost2 = $this->feePoint($a);
                        if ($subtotal + $cost2 <= $budget && !$chosen->contains($a->id)) {
                            $chosen->push($a->id);
                            $subtotal += $cost2;
                            $pool = $pool->reject(fn($r) => $r['a']->id === $a->id)->values();
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) break;
                }
            }
        }

        $score = [
            'fit' => round(1.0 - max(0, $subtotal - $budget) / max(1, $budget), 3),
            'diversity' => 0.5, // 다음 단계에서 태그/소속사/장르 분산 점수로 고도화
        ];
        // 최소 필요 인원 계산 (잠정: 요청 쿼터 합 + 고정 인원)
        $minNeeded = (int) $remainQuota->sum() + $lockedArtists->count();

        if ($chosen->count() < $minNeeded) {
            $pool = $candidates->reject(fn($a) => $chosen->contains($a->id));
            foreach ($pool->sortBy(fn($a) => $this->feePoint($a)) as $a) {
                $cost = $this->feePoint($a);
                if ($subtotal + $cost <= $budget) {
                    $chosen->push($a->id);
                    $subtotal += $cost;
                    if ($chosen->count() >= $minNeeded) break;
                }
            }
        }

        return [
            'artist_ids' => $chosen->values()->all(),
            'subtotal' => $subtotal,
            'score_breakdown' => $score,
        ];
    }

    protected function feePoint($artist): int
    {
        if ($artist->fee_min && $artist->fee_max) return (int) round(($artist->fee_min + $artist->fee_max) / 2);
        if ($artist->fee_min) return (int) $artist->fee_min;
        if ($artist->fee_max) return (int) $artist->fee_max;
        return 0;
    }

    // 별칭 사전
    protected array $genreAliases = [
        'k-pop' => 'kpop',
        'k pop' => 'kpop',
        'kpop' => 'kpop',
        '케이팝' => 'kpop',
        'kpop댄스' => 'kpop',
        'k-pop댄스' => 'kpop',
        'dance' => 'dance',
        '댄스' => 'dance',
        'hiphop' => 'hiphop',
        'hip-hop' => 'hiphop',
        '힙합' => 'hiphop',
        'r&b' => 'rnb',
        '알앤비' => 'rnb',
        'rnb' => 'rnb',
        'pop' => 'pop',
        '팝' => 'pop',
        'metal' => 'rock',
        '메탈' => 'rock',
        '록' => 'rock',
        '재즈' => 'jazz',
        'announcer' => 'announcer',
        '아나운서' => 'announcer',
        'comedian' => 'comedian',
        '개그맨' => 'comedian',
        'mc' => 'announcer',
        // 필요시 계속 추가
    ];

    protected function norm(string $g): string
    {
        $x = mb_strtolower(trim($g), 'UTF-8');
        $x = str_replace(['/', '\\', '_', ' '], '', $x);
        return $this->genreAliases[$x] ?? $x;
    }

    protected function hasGenre($artist, string $genre): bool
    {
        $want = $this->norm($genre);
        $g = $artist->genre ?? null;

        if (is_array($g)) {
            foreach ($g as $gg) {
                if ($this->norm((string)$gg) === $want) return true;
            }
            return false;
        }
        if (is_string($g)) {
            // 문자열을 토큰으로 쪼개서 비교
            $tokens = preg_split('/[,\s\/]+/u', $g, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            foreach ($tokens as $t) if ($this->norm($t) === $want) return true;
        }
        return false;
    }

    protected function groupByGenreBestEffort(Collection $artists): array
    {
        $map = [];
        foreach ($artists as $a) {
            $keys = [];
            // 1) 관계형 장르
            try {
                foreach (($a->genresRelation ?? []) as $g) {
                    $slug = (string)($g->slug ?? '');
                    $name = (string)($g->name ?? '');
                    if (str_contains($slug, '-')) $slug = explode('-', $slug, 2)[1];
                    $keys[] = $this->norm($slug);
                    $keys[] = $this->norm($name);
                    if (stripos($slug, 'rock') !== false) $keys[] = 'metal';
                }
            } catch (\Throwable $e) {}
            // 2) 레거시 배열/문자열
            if (empty($a->genresRelation) || count($a->genresRelation) === 0) {
                $list = [];
                if (is_array($a->genre)) $list = $a->genre;
                elseif (is_string($a->genre)) $list = preg_split('/[,\s\/]+/u', $a->genre, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                foreach ($list as $g) $keys[] = $this->norm((string)$g);
            }
            // 3) 분야 기반 요청 맵핑(사회/댄스)
            $disc = $a->discipline->slug ?? null;
            if ($disc === 'mc') { $keys[] = 'announcer'; $keys[] = 'comedian'; }
            if ($disc === 'dance') { $keys[] = 'kpop'; }

            $keys = array_values(array_unique(array_filter($keys)));
            foreach ($keys as $k) { $map[$k] ??= []; $map[$k][] = $a; }
        }
        return $map;
    }
}
