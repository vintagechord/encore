<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\GenerationRequest;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use App\Services\OptionGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    /** 폼 */
    public function create()
    {
        // 상위 카테고리
        $categories = [
            'music' => '음악 공연',
            'mc'    => '사회(MC)',
            'dance' => '댄스',
            'other' => '기타',
        ];

        // 세부 항목
        $musicGenres = [
            'kpop'       => 'K-POP',
            'pop'        => '팝',
            'rock'       => '록/메탈',
            'indie'      => '인디',
            'jazz'       => '재즈',
            'hiphop'     => '힙합',
            'rnb'        => 'R&B',
            'electronic' => '일렉트로닉',
            'classical'  => '클래식',
            'folk'       => '포크',
            'ballad'     => '발라드',
        ];

        $danceGenres = [
            'kpop'         => 'K-POP 댄스',
            'street'       => '스트릿(힙합/팝핑/락킹)',
            'contemporary' => '컨템포러리',
            'ballet'       => '발레',
            'traditional'  => '전통무용',
            'cheer'        => '치어/퍼포먼스',
        ];

        $mcRoles = [
            'announcer'  => '아나운서/행사진행',
            'comedian'   => '개그맨/코미디언 MC',
            'recreation' => '레크리에이션 강사',
        ];

        return view('inquiry.create', compact('categories', 'musicGenres', 'danceGenres', 'mcRoles'));
    }

    /** 저장 */
    public function store(Request $request)
    {
        // 1) 검증
        $validated = $request->validate([
            // 연락처
            'contact_name'  => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:50'],

            // 일정
            'event_start'   => ['required', 'date'],
            'event_end'     => ['nullable', 'date', 'after_or_equal:event_start'],
            'date_flexible' => ['nullable', 'boolean'],

            // 예산
            'budget_min'    => ['nullable', 'integer', 'min:0', 'lte:budget_max'],
            'budget_max'    => ['nullable', 'integer', 'min:0'],

            // 상위 카테고리(복수)
            'performance_categories'   => ['required', 'array', 'min:1'],
            'performance_categories.*' => [Rule::in(['music', 'dance', 'mc', 'other'])],

            // 세부 카운트(숫자)
            'music_counts'     => ['array'],
            'music_counts.*'   => ['nullable', 'integer', 'min:0', 'max:50'],

            'dance_counts'     => ['array'],
            'dance_counts.*'   => ['nullable', 'integer', 'min:0', 'max:50'],

            // 이름을 mc_counts 또는 mc_role_counts 로 보내도 받음
            'mc_counts'        => ['array'],
            'mc_counts.*'      => ['nullable', 'integer', 'min:0', 'max:50'],
            'mc_role_counts'   => ['array'],
            'mc_role_counts.*' => ['nullable', 'integer', 'min:0', 'max:50'],

            // 기타 선택 필드
            'city'                   => ['nullable', 'string', 'max:120'],
            'venue_type'             => ['nullable', 'string', 'max:120'],
            'indoor_outdoor'         => ['nullable', 'string', 'max:30'],
            'audience_size'          => ['nullable', 'integer', 'min:0'],
            'performance_type'       => ['nullable', 'string', 'max:120'],
            'set_duration'           => ['nullable', 'integer', 'min:0'],
            'sets_count'             => ['nullable', 'integer', 'min:1', 'max:10'],
            'requested_artist_name'  => ['nullable', 'string', 'max:190'],
            'notes'                  => ['nullable', 'string', 'max:5000'],
        ]);

        // 2) 보정
        if (empty($validated['event_end'])) {
            $validated['event_end'] = $validated['event_start'];
        }
        if (
            !empty($validated['budget_min']) && !empty($validated['budget_max']) &&
            $validated['budget_min'] > $validated['budget_max']
        ) {
            [$validated['budget_min'], $validated['budget_max']]
                = [$validated['budget_max'], $validated['budget_min']];
        }

        // 3) 장르별 카운트 정규화
        $selectedCats = (array)($validated['performance_categories'] ?? []);
        $musicCounts  = (array)($validated['music_counts']  ?? []);
        $danceCounts  = (array)($validated['dance_counts']  ?? []);
        $mcCounts     = (array)($validated['mc_counts'] ?? $request->input('mc_role_counts', []) ?? []);

        $genreCounts = [];

        if (in_array('music', $selectedCats, true)) {
            foreach ($musicCounts as $g => $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                if ($n > 0) $genreCounts[$g] = ($genreCounts[$g] ?? 0) + $n;
            }
        }
        if (in_array('dance', $selectedCats, true)) {
            $sum = 0;
            foreach ($danceCounts as $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                $sum += max(0, $n);
            }
            if ($sum > 0) $genreCounts['dance'] = ($genreCounts['dance'] ?? 0) + $sum;
        }
        if (in_array('mc', $selectedCats, true)) {
            $sum = 0;
            foreach ($mcCounts as $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                $sum += max(0, $n);
            }
            if ($sum > 0) $genreCounts['mc'] = ($genreCounts['mc'] ?? 0) + $sum;
        }

        // 4) 저장
        $categoryForColumn = count($selectedCats) === 1 ? $selectedCats[0] : 'multi';

        $payload = [
            'contact_name'          => $validated['contact_name'],
            'contact_email'         => $validated['contact_email'],
            'contact_phone'         => $validated['contact_phone'] ?? null,

            'budget_min'            => $validated['budget_min'] ?? null,
            'budget_max'            => $validated['budget_max'] ?? null,

            'event_start'           => $validated['event_start'],
            'event_end'             => $validated['event_end'],
            'date_flexible'         => (bool)($validated['date_flexible'] ?? false),

            'category'              => $categoryForColumn,
            'genre_counts'          => $genreCounts, // JSON

            'city'                  => $validated['city'] ?? null,
            'venue_type'            => $validated['venue_type'] ?? null,
            'indoor_outdoor'        => $validated['indoor_outdoor'] ?? null,
            'audience_size'         => $validated['audience_size'] ?? null,
            'performance_type'      => $validated['performance_type'] ?? null,
            'set_duration'          => $validated['set_duration'] ?? null,
            'sets_count'            => (int)($validated['sets_count'] ?? 1), // NOT NULL 회피
            'requested_artist_name' => $validated['requested_artist_name'] ?? null,
            'notes'                 => $validated['notes'] ?? null,
            'status'                => 'new',
        ];

        $intake = IntakeRequest::create($payload);

        // 옵션 미리보기로 이동
        return redirect()->route('inquiry.options', ['intake' => $intake->id]);
    }

    /** (구) 감사 페이지 */
    public function thanks()
    {
        return view('inquiry.thanks');
    }

    /** 관리자: 저장된 추천안 상세(HTML) */
    public function recommendations(IntakeRequest $intake)
    {
        $sets = RecommendationSet::where('intake_request_id', $intake->id)
            ->orderByDesc('id')
            ->get()
            ->transform(function ($s) {
                $s->items = is_string($s->items) ? json_decode($s->items, true) : ($s->items ?? []);
                return $s;
            });

        $artistIds = collect($sets)->flatMap(fn($s) => collect($s->items)->pluck('artist_id'))
            ->filter()->unique()->values();
        $artists = Artist::whereIn('id', $artistIds)->get()->keyBy('id');

        return view('admin.recommendations', [
            'intake'  => $intake,
            'sets'    => $sets,
            'artists' => $artists,
        ]);
    }

    /** 옵션 미리보기(자동 생성) */
    public function options(IntakeRequest $intake, Request $request, OptionGenerator $generator)
    {
        $seed        = (string)($request->input('seed') ?: Str::uuid());
        $optionCount = max(1, min(6, (int)$request->input('count', 3)));

        $locked   = collect((array)$request->input('locked', []))->map(fn($v) => (int)$v)->filter()->values()->all();
        $excluded = collect((array)$request->input('exclude', $request->input('excluded', [])))
            ->map(fn($v) => (int)$v)->filter()->values()->all();

        $genreCounts = is_array($intake->genre_counts) ? $intake->genre_counts : [];
        $budgetTotal = (int)($intake->budget_max ?? $intake->budget_min ?? 0);

        $gr = GenerationRequest::create([
            'budget_total'         => $budgetTotal,
            'event_start'          => $intake->event_start ?? now(),
            'event_end'            => $intake->event_end   ?? ($intake->event_start ?? now()),
            'genre_counts'         => $genreCounts,
            'locked_artist_ids'    => $locked,
            'excluded_artist_ids'  => $excluded,
            'seed'                 => $seed,
            'option_count'         => $optionCount,
        ]);

        $options   = $generator->generate($gr);
        $artistIds = collect($options)->flatMap(fn($o) => $o['artist_ids'])->unique()->values();
        $artists   = Artist::whereIn('id', $artistIds)->get()->keyBy('id');

        return view('inquiry.options', [
            'intake'      => $intake,
            'options'     => $options,
            'artists'     => $artists,
            'seed'        => $seed,
            'locked'      => $locked,
            'excluded'    => $excluded,
            'optionCount' => $optionCount,
        ]);
    }
}
