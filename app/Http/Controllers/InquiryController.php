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
    public function create(\Illuminate\Http\Request $request)
    {
        $prefMode = $request->query('mode');
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

        return view('inquiry.create', compact('categories', 'musicGenres', 'danceGenres', 'mcRoles', 'prefMode'));
    }

    /** 저장 */
    public function store(Request $request)
    {
        // 0) 모드 확인 (instant | one_day)
        $mode = $request->input('request_mode', 'instant');

        // 1) 검증 (모드별 차등 규칙 적용)
        $validated = $request->validate([
            // 연락처
            'contact_name'  => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:50'],

            // 일정
            'event_start'   => [($mode === 'one_day') ? 'nullable' : 'required', 'date'],
            'event_end'     => ['nullable', 'date', 'after_or_equal:event_start'],
            'date_flexible' => ['nullable', 'boolean'],

            // 예산
            'budget_min'    => ['nullable', 'integer', 'min:0', 'lte:budget_max'],
            'budget_max'    => ['nullable', 'integer', 'min:0'],

            // 상위 카테고리(복수): 1일 Set은 선택 사항, 존재할 때만 배열 검증
            'performance_categories'   => ($mode === 'one_day')
                ? ['sometimes','array']
                : ['required','array','min:1'],
            'performance_categories.*' => [Rule::in(['music','dance','mc','performance','planned','celebrity','foreign','other'])],

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

            // 확장 분류 카운트(선택)
            'performance_counts' => ['array'],
            'performance_counts.*' => ['nullable','integer','min:0','max:50'],
            'classic_counts' => ['array'],
            'classic_counts.*' => ['nullable','integer','min:0','max:50'],
            'traditional_counts' => ['array'],
            'traditional_counts.*' => ['nullable','integer','min:0','max:50'],
            'planned_counts' => ['array'],
            'planned_counts.*' => ['nullable','integer','min:0','max:50'],
            'celebrity_counts' => ['array'],
            'celebrity_counts.*' => ['nullable','integer','min:0','max:50'],
            'foreign_counts' => ['array'],
            'foreign_counts.*' => ['nullable','integer','min:0','max:50'],

            // 기타 선택 필드
            'city'                   => ['nullable', 'string', 'max:120'],
            'venue_type'             => ['nullable', 'string', 'max:120'],
            'indoor_outdoor'         => ['nullable', 'string', 'max:30'],
            'audience_size'          => ['nullable', 'integer', 'min:0'],
            'performance_type'       => ['nullable', 'string', 'max:120'],
            'set_duration'           => ['nullable', 'integer', 'min:0'],
            'sets_count'             => ['nullable', 'integer', 'min:1', 'max:10'],
            // direct 요청은 별도 폼/컨트롤러에서 접수
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

        // one-day 추가 필수 요구사항 체크
        if ($mode === 'one_day' && !trim((string)$request->input('requirements',''))) {
            return back()->withErrors(['requirements' => '1일 Set은 요구사항 입력이 필수입니다.'])->withInput();
        }

        // 3) 장르별 카운트 정규화
        $selectedCats = (array)($validated['performance_categories'] ?? []);
        $musicCounts  = (array)($validated['music_counts']  ?? []);
        $danceCounts  = (array)($validated['dance_counts']  ?? []);
        $mcCounts     = (array)($validated['mc_counts'] ?? $request->input('mc_role_counts', []) ?? []);
        $performanceCounts = (array)($validated['performance_counts'] ?? []);
        $plannedCounts     = (array)($validated['planned_counts'] ?? []);
        $celebrityCounts   = (array)($validated['celebrity_counts'] ?? []);
        $foreignCounts     = (array)($validated['foreign_counts'] ?? []);

        $genreCounts = [];

        if (in_array('music', $selectedCats, true)) {
            foreach ($musicCounts as $g => $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                if ($n > 0) $genreCounts[$g] = ($genreCounts[$g] ?? 0) + $n;
            }
        }
        if (in_array('dance', $selectedCats, true)) {
            foreach ($danceCounts as $g => $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                if ($n > 0) $genreCounts[$g] = ($genreCounts[$g] ?? 0) + $n;
            }
        }
        if (in_array('mc', $selectedCats, true)) {
            foreach ($mcCounts as $g => $n) {
                $n = is_numeric($n) ? (int)$n : 0;
                if ($n > 0) $genreCounts[$g] = ($genreCounts[$g] ?? 0) + $n;
            }
        }

        // 확장 분류도 세부 키별로 합산
        $mergeCounts = function(array $src) use (&$genreCounts) {
            foreach ($src as $k => $v) {
                $n = is_numeric($v) ? (int)$v : 0;
                if ($n > 0) $genreCounts[$k] = ($genreCounts[$k] ?? 0) + $n;
            }
        };
        if (in_array('performance', $selectedCats, true)) $mergeCounts($performanceCounts);
        if (in_array('planned', $selectedCats, true)) $mergeCounts($plannedCounts);
        if (in_array('celebrity', $selectedCats, true)) $mergeCounts($celebrityCounts);
        if (in_array('foreign', $selectedCats, true)) $mergeCounts($foreignCounts);

        // 4) 저장
        // 카테고리 컬럼은 기능별 식별에 사용: 옵션 흐름은 항상 'option'로 저장
        $categoryForColumn = 'option';

        // notes: include request_type for labeling (instant)
        $notesData = [
            'request_type' => in_array($mode, ['instant','one_day'], true) ? $mode : 'instant',
            'notes_text'   => $validated['notes'] ?? null,
            'requirements' => $mode === 'one_day' ? (string)$request->input('requirements','') : null,
        ];

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
            'notes'                 => json_encode($notesData, JSON_UNESCAPED_UNICODE),
            'status'                => ($mode === 'one_day') ? 'processing' : 'new',
        ];

        $intake = IntakeRequest::create($payload);

        // 모드별 리디렉트: 1일 Set은 접수 완료 페이지로, 1초 Set은 옵션 선택으로
        if ($mode === 'one_day') {
            return redirect()->route('inquiry.thanks');
        }
        return redirect()->route('inquiry.select_options', ['intake' => $intake->id]);
    }

    /** 1일 Set: 설명을 포함해 접수(관리자 설정 후 전달) */
    public function oneDayCreate()
    {
        return view('inquiry.one_day');
    }

    public function oneDayStore(Request $request)
    {
        $data = $request->validate([
            'contact_name'  => ['required','string','max:100'],
            'contact_email' => ['required','email','max:190'],
            'contact_phone' => ['nullable','string','max:50'],
            'event_start'   => ['nullable','date'],
            'event_end'     => ['nullable','date','after_or_equal:event_start'],
            'budget_min'    => ['nullable','integer','min:0','lte:budget_max'],
            'budget_max'    => ['nullable','integer','min:0'],
            'requirements'  => ['required','string','max:4000'],
        ]);

        if (empty($data['event_end']) && !empty($data['event_start'])) $data['event_end'] = $data['event_start'];
        if (!empty($data['budget_min']) && !empty($data['budget_max']) && $data['budget_min'] > $data['budget_max']) {
            [$data['budget_min'],$data['budget_max']] = [$data['budget_max'],$data['budget_min']];
        }

        $notesData = [
            'request_type' => 'one_day',
            'requirements' => $data['requirements'],
        ];

        $intake = \App\Models\IntakeRequest::create([
            'contact_name'  => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'contact_phone' => $data['contact_phone'] ?? null,
            'event_start'   => $data['event_start'] ?? null,
            'event_end'     => $data['event_end'] ?? ($data['event_start'] ?? null),
            'budget_min'    => $data['budget_min'] ?? null,
            'budget_max'    => $data['budget_max'] ?? null,
            'category'      => 'option',
            'genre_counts'  => [],
            'notes'         => json_encode($notesData, JSON_UNESCAPED_UNICODE),
            'status'        => 'processing', // 1일 후 전달 예정
        ]);

        return redirect()->route('inquiry.thanks');
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
        $rawEx = $request->input('exclude', $request->input('excluded', []));
        $excluded = collect(is_string($rawEx)
                ? preg_split('/[\s,]+/', $rawEx, -1, PREG_SPLIT_NO_EMPTY)
                : (array)$rawEx)
            ->map(fn($v) => (int)$v)
            ->filter()
            ->values()
            ->all();

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

    /** 신규: 캐러셀 UI용 옵션 선택 페이지 */
    public function selectOptions(IntakeRequest $intake, Request $request, OptionGenerator $generator)
    {
        $seed        = (string)($request->input('seed') ?: Str::uuid());
        $optionCount = max(1, min(6, (int)$request->input('count', 3)));

        $genreCounts = is_array($intake->genre_counts) ? $intake->genre_counts : [];
        $budgetTotal = (int)($intake->budget_max ?? $intake->budget_min ?? 0);
        $excluded = collect((array)$request->input('exclude', $request->input('excluded', [])))
            ->map(fn($v) => (int)$v)->filter()->values()->all();

        $gr = GenerationRequest::create([
            'budget_total'         => $budgetTotal,
            'event_start'          => $intake->event_start ?? now(),
            'event_end'            => $intake->event_end   ?? ($intake->event_start ?? now()),
            'genre_counts'         => $genreCounts,
            'locked_artist_ids'    => [],
            'excluded_artist_ids'  => $excluded,
            'seed'                 => $seed,
            'option_count'         => $optionCount,
        ]);

        $genOptions = $generator->generate($gr);
        $artistIds  = collect($genOptions)->flatMap(fn($o) => $o['artist_ids'])->unique()->values();
        $artists    = Artist::whereIn('id', $artistIds)->get()->keyBy('id');

        $options = [];
        foreach ($genOptions as $k => $og) {
            $min = 0; $max = 0; $alist = [];
            foreach ($og['artist_ids'] as $aid) {
                $a = $artists->get($aid);
                if (!$a) continue;
                $fr = $a->fee_range ?? null;
                if (is_array($fr)) {
                    $min += (int)($fr['min'] ?? 0);
                    $max += (int)($fr['max'] ?? 0);
                }
                $alist[] = [
                    'title'     => $a->stage_name ?? $a->name ?? '아티스트',
                    'image'     => $a->image_url ?? null,
                    'fee_min'   => $fr['min'] ?? null,
                    'fee_max'   => $fr['max'] ?? null,
                    'artist_id' => $a->id,
                ];
            }
            $options[$k] = [
                'index'      => $k,
                'label'      => '옵션 '.($k+1),
                'artists'    => $alist,
                'budget_min' => $min ?: null,
                'budget_max' => $max ?: null,
            ];
        }

        // 추천셋이 아직 없다면 간단 저장 (대시보드 노출용)
        try {
            if ($intake->recommendationSets()->count() === 0) {
                $flat = [];
                $rank = 1;
                foreach ($options as $opt) {
                    foreach ($opt['artists'] as $row) {
                        $flat[] = [
                            'artist_id' => $row['artist_id'] ?? null,
                            'title'     => $row['title'] ?? null,
                            'fee_min'   => $row['fee_min'] ?? null,
                            'fee_max'   => $row['fee_max'] ?? null,
                            'rank'      => $rank++,
                        ];
                    }
                }
                \App\Models\RecommendationSet::create([
                    'intake_request_id' => $intake->id,
                    'label' => '자동 생성 추천셋',
                    'items' => $flat,
                    'total_cost' => null,
                    'rationale' => '요청 장르/역할 기반 자동 생성',
                ]);
            }
        } catch (\Throwable $e) { /* ignore */ }

        return view('inquiry.select_options', [
            'intake'  => $intake,
            'options' => $options,
            'seed'    => $seed,
        ]);
    }

    /** 신규: 옵션 리포트(토큰 없이도, intake+seed 기반) */
    public function previewOption(IntakeRequest $intake, int $idx, Request $request, OptionGenerator $generator)
    {
        $seed        = (string)($request->input('seed') ?: Str::uuid());
        $optionCount = max(1, min(6, (int)$request->input('count', 3)));

        $genreCounts = is_array($intake->genre_counts) ? $intake->genre_counts : [];
        $budgetTotal = (int)($intake->budget_max ?? $intake->budget_min ?? 0);

        $gr = GenerationRequest::create([
            'budget_total'         => $budgetTotal,
            'event_start'          => $intake->event_start ?? now(),
            'event_end'            => $intake->event_end   ?? ($intake->event_start ?? now()),
            'genre_counts'         => $genreCounts,
            'locked_artist_ids'    => [],
            'excluded_artist_ids'  => [],
            'seed'                 => $seed,
            'option_count'         => max($optionCount, $idx + 1),
        ]);

        $genOptions = $generator->generate($gr);
        $artistIds  = collect($genOptions)->flatMap(fn($o) => $o['artist_ids'])->unique()->values();
        $artists    = Artist::whereIn('id', $artistIds)->get()->keyBy('id');

        // Build detailed option for view
        $selected = $genOptions[$idx] ?? null;
        if (!$selected) abort(404);

        $min = 0; $max = 0; $alist = [];
        foreach ($selected['artist_ids'] as $aid) {
            $a = $artists->get($aid);
            if (!$a) continue;
            $fr = $a->fee_range ?? null;
            if (is_array($fr)) { $min += (int)($fr['min'] ?? 0); $max += (int)($fr['max'] ?? 0); }
            $alist[] = [
                'title'     => $a->stage_name ?? $a->name ?? '아티스트',
                'image'     => $a->image_url ?? null,
                'fee_min'   => $fr['min'] ?? null,
                'fee_max'   => $fr['max'] ?? null,
                'artist_id' => $a->id,
            ];
        }

        $option = [
            'index'      => $idx,
            'label'      => '옵션 '.($idx+1),
            'artists'    => $alist,
            'budget_min' => $min ?: null,
            'budget_max' => $max ?: null,
        ];

        // options 목록 일부도 전달(썸네일 등 필요시)
        $options = [];
        foreach ($genOptions as $k => $og) {
            if ($k === $idx) { $options[$k] = $option; continue; }
            $min2 = 0; $max2 = 0; $alist2 = [];
            foreach ($og['artist_ids'] as $aid) {
                $a = $artists->get($aid); if (!$a) continue;
                $fr = $a->fee_range ?? null; if (is_array($fr)) { $min2 += (int)($fr['min'] ?? 0); $max2 += (int)($fr['max'] ?? 0); }
                $alist2[] = [ 'title'=>$a->stage_name ?? $a->name ?? '아티스트', 'image'=>$a->image_url ?? null, 'fee_min'=>$fr['min'] ?? null, 'fee_max'=>$fr['max'] ?? null, 'artist_id'=>$a->id ];
            }
            $options[$k] = [ 'index'=>$k, 'label'=>'옵션 '.($k+1), 'artists'=>$alist2, 'budget_min'=>$min2 ?: null, 'budget_max'=>$max2 ?: null ];
        }

        return view('inquiry.option_report', [
            'intake' => $intake,
            'option' => $option,
            'options'=> $options,
            'index'  => $idx,
            'seed'   => $seed,
        ]);
    }
}
