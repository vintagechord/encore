<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>아티스트 탐색 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body { margin:0; background: var(--bg); color: var(--fg); font-family: var(--font-sans); }
    .explore { padding: 26px 0 56px; }
    .explore-hero { display: grid; gap: 10px; margin-bottom: 18px; }
    .explore-hero h1 { margin: 0; font-size: clamp(26px, 3.4vw, 36px); letter-spacing: -0.02em; }
    .explore-hero p { margin: 0; color: var(--muted); max-width: 520px; }
    .explore-eyebrow { font-size: 12px; letter-spacing: 0.28em; text-transform: uppercase; color: var(--accent); font-weight: 700; }

    .filter-form { display: grid; gap: 22px; }
    .filter-section { background: transparent; }
    .filter-section-head { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom: 10px; }
    .filter-section-head h2 { margin: 0; font-size: 16px; }
    .filter-toggle { height: 34px; padding: 0 12px; border-radius: 10px; border: 1px solid var(--border); background: var(--card); color: var(--fg); cursor: pointer; font-weight: 600; }
    .filter-toggle:hover { border-color: var(--accent); }
    .filter-section-body { display: block; }
    .filter-quick { display: grid; gap: 12px; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 14px; }
    .filter-quick .field { display: grid; gap: 6px; }
    .filter-quick label { font-size: 12px; color: var(--muted); }
    .filter-quick input,
    .filter-quick select { width: 100%; height: 44px; padding: 0 12px; border-radius: 12px; border: 1px solid var(--border); background: var(--card); color: var(--fg); }
    .filter-quick .cta { display:flex; align-items: end; }
    .filter-quick .cta button { width: 100%; height: 44px; border-radius: 12px; border: 1px solid var(--btn); background: var(--btn); color: var(--btn-text); font-weight: 700; cursor: pointer; }
    .filter-quick .cta button:hover { background: var(--btn-hover); border-color: var(--btn-hover); }
    .filter-quick .cta button[disabled] { background: var(--card-alt); border-color: var(--border); color: var(--muted); cursor: not-allowed; }

    .explore-layout { display: grid; gap: 20px; grid-template-columns: 260px 1fr; align-items: start; }
    .filter-panel { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 16px; position: sticky; top: 168px; }
    .filter-block { display: grid; gap: 8px; margin-bottom: 16px; }
    .filter-block label { font-size: 12px; color: var(--muted); }
    .filter-block input,
    .filter-block select { width: 100%; height: 42px; padding: 0 10px; border-radius: 10px; border: 1px solid var(--border); background: var(--card-alt); color: var(--fg); }
    .filter-block .chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .filter-block .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border); background: rgba(243,198,82,.12); font-size: 12px; color: var(--fg); text-decoration: none; }

    .results-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .results-count { color: var(--muted); font-size: 13px; }
    .filter-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .filter-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--chip-border); background: var(--chip); color: var(--fg); font-size: 12px; text-decoration: none; }
    .filter-chip .x { font-weight: 800; opacity: .6; }
    .filter-chip:hover { border-color: var(--accent); background: rgba(243,198,82,.2); }
    .filter-clear { margin-left: auto; font-size: 12px; color: var(--muted); text-decoration: none; }
    .filter-clear:hover { color: var(--accent-hover); }
    .artist-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .artist-card { display: grid; grid-template-columns: 140px 1fr; gap: 14px; padding: 14px; background: rgba(21,16,18,0.88); border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 18px 30px rgba(10,10,10,0.18); }
    [data-theme="light"] .artist-card { background: rgba(255,255,255,0.9); }
    .artist-media { display: block; width: 100%; aspect-ratio: 4/3; border-radius: 12px; overflow: hidden; background: var(--card-alt); }
    .artist-media img { width: 100%; height: 100%; object-fit: cover; }
    .artist-body h3 { margin: 0 0 6px; font-size: 18px; }
    .artist-body h3 a { color: inherit; text-decoration: none; }
    .artist-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; border: 1px solid var(--chip-border); background: var(--chip); font-size: 12px; color: var(--fg); }
    .artist-desc { margin: 6px 0 10px; color: var(--muted); font-size: 13px; }
    .artist-meta { display: flex; flex-wrap: wrap; gap: 8px; font-size: 12px; color: var(--muted); }
    .artist-actions { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
    .artist-actions .btn { display: inline-flex; align-items: center; justify-content: center; height: 36px; padding: 0 12px; border-radius: 10px; border: 1px solid var(--btn); background: var(--btn); color: var(--btn-text); text-decoration: none; font-weight: 600; }
    .artist-actions .btn:hover { background: var(--btn-hover); border-color: var(--btn-hover); }
    .artist-actions .btn.ghost { background: transparent; color: var(--fg); border-color: var(--border); }
    .artist-actions .btn.fav { background: transparent; border-color: var(--chip-border); color: var(--fg); }
    .artist-actions .btn.fav.active { background: var(--btn); border-color: var(--btn); color: var(--btn-text); }
    .artist-actions form { margin: 0; }

    .empty-state { padding: 32px; border: 1px dashed var(--border); border-radius: 16px; color: var(--muted); text-align: center; }

    @media (max-width: 1200px) {
      .artist-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 980px) {
      .filter-quick { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .explore-layout { grid-template-columns: 1fr; }
      .filter-panel { position: static; }
      .filter-section-body { display: none; }
      .filter-section.is-open .filter-section-body { display: block; }
      .filter-toggle { display: inline-flex; }
      .filter-block[data-filter-group] { display: none; }
      .artist-card { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .filter-quick { grid-template-columns: repeat(1, minmax(0, 1fr)); }
      .artist-actions { flex-direction: column; align-items: stretch; }
      .filter-section-head h2 { font-size: 15px; }
    }
    @media (min-width: 981px) {
      .filter-toggle { display: none; }
      .filter-section-body { display: block !important; }
    }

    .range-wrap { display: grid; gap: 10px; }
    .range-row { display:flex; gap:10px; align-items:center; }
    .range-label { width:40px; color: var(--muted); font-weight: 600; font-size: 12px; }
    .range-field { position: relative; flex: 1; min-width: 180px; padding: 0 14px; }
    .range { width: 100%; height: 10px; border-radius: 999px; background: linear-gradient(to right, var(--accent) 0 var(--p,0%), rgba(148,163,184,.25) var(--p,0%)); outline:none; -webkit-appearance:none; appearance:none; }
    .range::-webkit-slider-thumb { -webkit-appearance:none; appearance:none; width:18px; height:18px; border-radius:50%; background: var(--accent); border: 2px solid #fff3; box-shadow: 0 2px 6px rgba(0,0,0,.25); cursor:pointer; }
    .range::-moz-range-thumb { width:18px; height:18px; border-radius:50%; background: var(--accent); border: 2px solid #fff3; box-shadow: 0 2px 6px rgba(0,0,0,.25); cursor:pointer; }
    .range-bubble { position:absolute; transform: translateX(-50%); background: var(--card-alt); color: var(--fg); border:1px solid var(--border); padding:2px 8px; border-radius:8px; font-size:11px; white-space:nowrap; z-index: 2; pointer-events:none; }
    .range-bubble.bubble-top { top:-30px; }
    .range-bubble.bubble-bottom { bottom:-30px; }
  </style>
</head>
<body>
  @include('public.partials.header')
  @php
    use Illuminate\Support\Str;
    $filters = $filters ?? [];
    $selectedDiscipline = $filters['discipline'] ?? $activeCategory ?? 'all';
    $selectedDiscipline = $selectedDiscipline === '' ? 'all' : $selectedDiscipline;
    $disciplineMap = collect($categories ?? [])->mapWithKeys(fn($row) => [$row['slug'] => $row['id'] ?? null]);
    $activeLabelRow = collect($categories ?? [])->firstWhere('slug', $selectedDiscipline);
    $activeLabel = $selectedDiscipline === 'all' ? '전체 카테고리' : ($activeLabelRow['name'] ?? '선택된 분야');
    $appliedFilters = [];
    $labelMap = [
      'region' => '지역',
      'price' => '예상 금액대',
      'min_price' => '최소 금액',
      'max_price' => '최대 금액',
      'discipline' => '분야',
      'q' => '이름/팀명',
      'age_group' => '연령대',
      'team_type' => '팀 구성',
      'genre' => '장르',
      'mc_type' => '사회자 분류',
      'mc_style' => '진행 스타일',
      'dance_style' => '댄스 스타일',
      'performance_type' => '퍼포먼스 유형',
      'plan_type' => '기획공연 유형',
      'celebrity_type' => '셀럽 분야',
      'career' => '경력',
    ];
    foreach ($labelMap as $key => $label) {
      $val = $filters[$key] ?? '';
      if ($val === '' || $val === null || $val === 'all') continue;
      if ($key === 'price') {
        $val = str_replace(['0-2000000','2000000-5000000','5000000-10000000','10000000+'], ['200만원 이하','200만~500만원','500만~1000만원','1000만원 이상'], (string)$val);
      }
      if ($key === 'min_price' || $key === 'max_price') {
        $val = number_format((int)$val).'원';
      }
      if ($key === 'discipline') {
        $match = collect($categories)->firstWhere('slug', $val);
        $val = $match['name'] ?? $val;
      }
      $appliedFilters[] = ['key' => $key, 'label' => $label, 'value' => $val];
    }
    $clearUrl = route('artists.browse');
    $favoriteIds = $favoriteIds ?? [];
    $minPriceVal = is_numeric($filters['min_price'] ?? null) ? (int) $filters['min_price'] : 0;
    $maxPriceVal = is_numeric($filters['max_price'] ?? null) ? (int) $filters['max_price'] : 3000000;

    $fallbackGenres = [
      'music' => ['K-POP','발라드','트로트','힙합','R&B/Soul','인디','밴드','재즈/소울','클래식','국악','OST'],
      'mc' => ['전문 MC','아나운서','홈쇼핑','주례','돌잔치','기업행사','학술/포럼','국제행사'],
      'dance' => ['스트릿','K-POP','비보이','락킹','왁킹','팝핑','현대무용','발레','치어'],
      'performance' => ['마술','서커스','LED','저글링','샌드아트','파이어쇼','버블쇼'],
      'plan' => ['기획공연','테마공연','쇼케이스','콜라보','패키지','오프닝','피날레'],
      'celebrity' => ['셀럽','인플루언서','배우/방송인','유튜버','스포츠스타','셰프/쿠킹'],
    ];
  @endphp

  <main class="enc-container explore">
    <section class="explore-hero">
      <span class="explore-eyebrow">ARTIST SEARCH</span>
      <h1>가격 · 지역 기준으로 아티스트를 바로 찾으세요</h1>
      <p>분야별 카테고리와 세부 필터로 실제 섭외에 필요한 조건만 빠르게 좁혀드립니다.</p>
    </section>

    <form class="filter-form" method="get" action="{{ route('artists.browse') }}">
      <div class="filter-section" data-section="quick">
        <div class="filter-section-head">
          <h2>메인 조건</h2>
          <button class="filter-toggle" type="button" data-target="quick" aria-expanded="false">조건 펼치기</button>
        </div>
        <div class="filter-section-body" id="filter-quick-body">
          <div class="filter-quick">
            <div class="field">
              <label for="region">지역</label>
              <select id="region" name="region" data-auto-submit="1">
                <option value="">전체</option>
                @foreach(['서울','경기','인천','강원','대전','세종','충북','충남','광주','전북','전남','대구','경북','부산','울산','경남','제주'] as $region)
                  <option value="{{ $region }}" @selected(($filters['region'] ?? '') === $region)>{{ $region }}</option>
                @endforeach
              </select>
            </div>
            <div class="field">
              <label for="price">예상 금액대</label>
              <select id="price" name="price" data-auto-submit="1">
                <option value="">전체</option>
                <option value="0-2000000" @selected(($filters['price'] ?? '') === '0-2000000')>200만원 이하</option>
                <option value="2000000-5000000" @selected(($filters['price'] ?? '') === '2000000-5000000')>200만~500만원</option>
                <option value="5000000-10000000" @selected(($filters['price'] ?? '') === '5000000-10000000')>500만~1000만원</option>
                <option value="10000000+" @selected(($filters['price'] ?? '') === '10000000+')>1000만원 이상</option>
              </select>
            </div>
            <div class="field">
              <label for="discipline">분야</label>
              <select id="discipline" name="discipline" data-auto-submit="1">
                <option value="all">전체</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat['slug'] }}" @selected($selectedDiscipline === $cat['slug'])>{{ $cat['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="field">
              <label for="q">이름/팀명</label>
              <input id="q" name="q" placeholder="아티스트 이름" value="{{ $filters['q'] ?? '' }}">
            </div>
            <div class="cta">
              <button type="button" id="searchBtn" @disabled(empty($filters['q']))>이름 검색</button>
            </div>
          </div>
        </div>
      </div>

      <div class="explore-layout">
        <aside class="filter-panel filter-section" data-section="detail">
          <div class="filter-section-head">
            <h2>상세 조건</h2>
            <button class="filter-toggle" type="button" data-target="detail" aria-expanded="false">조건 펼치기</button>
          </div>
          <div class="filter-section-body" id="filter-detail-body">

          <div class="filter-block">
            <label>예산 범위 (KRW)</label>
            <div class="range-wrap">
              <div class="range-row">
                <span class="range-label">최소</span>
                <div class="range-field">
                  <input class="range" id="range_min" name="min_price" type="range" min="0" max="300000000" step="100000" value="{{ $minPriceVal }}">
                  <output id="bubble_min" class="range-bubble bubble-top">{{ number_format($minPriceVal) }}원</output>
                </div>
              </div>
              <div class="range-row">
                <span class="range-label">최대</span>
                <div class="range-field">
                  <input class="range" id="range_max" name="max_price" type="range" min="0" max="300000000" step="100000" value="{{ $maxPriceVal }}">
                  <output id="bubble_max" class="range-bubble bubble-bottom">{{ number_format($maxPriceVal) }}원</output>
                </div>
              </div>
            </div>
          </div>

          <div class="filter-block">
            <label for="age_group">연령대</label>
            <select id="age_group" name="age_group" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['10대','20대','30대','40대','50대 이상'] as $age)
                <option value="{{ $age }}" @selected(($filters['age_group'] ?? '') === $age)>{{ $age }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="music">
            <label for="team_type">팀 구성</label>
            <select id="team_type" name="team_type" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['솔로','팀','혼성','듀엣','트리오','콰르텟','밴드','오케스트라'] as $team)
                <option value="{{ $team }}" @selected(($filters['team_type'] ?? '') === $team)>{{ $team }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="music">
            <label for="genre">장르</label>
            <select id="genre" name="genre" data-auto-submit="1">
              <option value="">선택 안함</option>
              @if(!empty($genreOptions) && $genreOptions->count())
                @foreach($genreOptions as $g)
                  @php
                    $slug = null;
                    if ($g->discipline_id) {
                      $slug = $disciplineMap->search($g->discipline_id, true);
                    }
                  @endphp
                  <option value="{{ $g->name }}" data-discipline="{{ $slug ?? 'all' }}" @selected(($filters['genre'] ?? '') === $g->name)>{{ $g->name }}</option>
                @endforeach
              @else
                @foreach($fallbackGenres[$selectedDiscipline] ?? $fallbackGenres['music'] as $g)
                  <option value="{{ $g }}" @selected(($filters['genre'] ?? '') === $g)>{{ $g }}</option>
                @endforeach
              @endif
            </select>
          </div>

          <div class="filter-block" data-filter-group="music">
            <label for="career">경력</label>
            <select id="career" name="career" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['1년 미만','1~3년','3~7년','7년 이상'] as $career)
                <option value="{{ $career }}" @selected(($filters['career'] ?? '') === $career)>{{ $career }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="mc">
            <label for="mc_type">사회자 분류</label>
            <select id="mc_type" name="mc_type" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['전문MC','아나운서','홈쇼핑','주례','돌잔치','기업행사'] as $mc)
                <option value="{{ $mc }}" @selected(($filters['mc_type'] ?? '') === $mc)>{{ $mc }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="mc">
            <label for="mc_style">진행 스타일</label>
            <select id="mc_style" name="mc_style" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['차분한','활발한','밝은','유머러스','진중한','차분+유쾌'] as $style)
                <option value="{{ $style }}" @selected(($filters['mc_style'] ?? '') === $style)>{{ $style }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="dance">
            <label for="dance_style">댄스 스타일</label>
            <select id="dance_style" name="dance_style" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['스트릿','K-POP','비보이','락킹','왁킹','팝핑','현대무용','발레','댄스스포츠','치어'] as $style)
                <option value="{{ $style }}" @selected(($filters['dance_style'] ?? '') === $style)>{{ $style }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="performance">
            <label for="performance_type">퍼포먼스 유형</label>
            <select id="performance_type" name="performance_type" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['마술','서커스','LED','저글링','샌드아트','파이어쇼','버블쇼','마임','퍼레이드'] as $type)
                <option value="{{ $type }}" @selected(($filters['performance_type'] ?? '') === $type)>{{ $type }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="plan">
            <label for="plan_type">기획공연 유형</label>
            <select id="plan_type" name="plan_type" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['기획공연','테마공연','쇼케이스','콜라보','패키지','오프닝','피날레','레퍼토리'] as $type)
                <option value="{{ $type }}" @selected(($filters['plan_type'] ?? '') === $type)>{{ $type }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="celebrity">
            <label for="celebrity_type">셀럽 분야</label>
            <select id="celebrity_type" name="celebrity_type" data-auto-submit="1">
              <option value="">선택 안함</option>
              @foreach(['셀럽','인플루언서','배우/방송인','유튜버','틱톡커','스포츠스타','셰프/쿠킹'] as $type)
                <option value="{{ $type }}" @selected(($filters['celebrity_type'] ?? '') === $type)>{{ $type }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block">
            <label>빠른 필터</label>
            <div class="chips">
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'music'])) }}">음악</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'mc'])) }}">사회(MC)</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'dance'])) }}">댄스</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'performance'])) }}">퍼포먼스</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'plan'])) }}">기획공연</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'celebrity'])) }}">셀럽</a>
              <a class="chip" href="{{ route('artists.browse') }}">전체 해제</a>
            </div>
          </div>
          </div>
        </aside>

        <section>
          <div class="results-head">
            <div class="results-count">총 {{ $artists->total() }}팀</div>
            <div class="results-count">{{ $activeLabel }}</div>
          </div>
          @if(!empty($appliedFilters))
            <div class="filter-chips">
              @foreach($appliedFilters as $chip)
                @php $nextQuery = request()->except($chip['key']); @endphp
                <a class="filter-chip" href="{{ route('artists.browse', $nextQuery) }}" data-scroll-save="1">
                  <span>{{ $chip['label'] }}: {{ $chip['value'] }}</span>
                  <span class="x">×</span>
                </a>
              @endforeach
              <a class="filter-clear" href="{{ $clearUrl }}" data-scroll-save="1">전체 해제</a>
            </div>
          @endif

          <div class="artist-grid">
            @forelse($artists as $artist)
              @php
                $img = $artist->image_url ?? ($artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image ?? null));
                $intro = $artist->bio ?? data_get($artist->meta ?? [], 'intro') ?? $artist->notes ?? '';
                $feeMin = $artist->fee_min ?? $artist->min_fee ?? null;
                $feeMax = $artist->fee_max ?? $artist->max_fee ?? null;
                $disciplineName = $artist->discipline->name ?? null;
                $isFav = in_array($artist->id, $favoriteIds, true);
              @endphp
              <article class="artist-card">
                <a class="artist-media" href="{{ route('artist.show', ['artist' => $artist->id]) }}">
                  @if($img)
                    <img src="{{ $img }}" alt="{{ $artist->name }} 이미지" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                  @else
                    <span style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:12px;">이미지 준비중</span>
                  @endif
                </a>
                <div class="artist-body">
                  <h3><a href="{{ route('artist.show', ['artist' => $artist->id]) }}">{{ $artist->name ?? $artist->stage_name }}</a></h3>
                  @if($disciplineName)
                    <span class="artist-pill">{{ $disciplineName }}</span>
                  @endif
                  <p class="artist-desc">{{ Str::limit(strip_tags($intro), 90) }}</p>
                  <div class="artist-meta">
                    @if($artist->home_city)
                      <span>지역: {{ $artist->home_city }}</span>
                    @endif
                    @if($feeMin || $feeMax)
                      <span>가격: {{ $feeMin ? number_format($feeMin) : '?' }} ~ {{ $feeMax ? number_format($feeMax) : '?' }}원</span>
                    @endif
                  </div>
                  <div class="artist-actions">
                    @auth
                      <form method="post" action="{{ $isFav ? route('favorites.destroy', $artist) : route('favorites.store', $artist) }}">
                        @csrf
                        @if($isFav)
                          @method('delete')
                        @endif
                        <button class="btn fav{{ $isFav ? ' active' : '' }}" type="submit">
                          {{ $isFav ? '찜 해제' : '찜하기' }}
                        </button>
                      </form>
                    @else
                      <a class="btn fav" href="{{ route('login') }}">찜하기</a>
                    @endauth
                    <a class="btn ghost" href="{{ route('artist.show', ['artist' => $artist->id]) }}">상세 보기</a>
                    <a class="btn" href="{{ route('direct.request.create', ['requested' => $artist->name ?? $artist->stage_name]) }}">의뢰하기</a>
                  </div>
                </div>
              </article>
            @empty
              <div class="empty-state">조건에 맞는 아티스트가 없습니다. 필터를 완화하거나 다른 분야를 선택해 주세요.</div>
            @endforelse
          </div>

          @if(method_exists($artists, 'links'))
            <div style="margin-top:18px;">{{ $artists->links() }}</div>
          @endif
        </section>
      </div>
    </form>
  </main>

  @include('public.partials.footer')

  <script>
    (function(){
      const form = document.querySelector('.filter-form');
      const searchBtn = document.getElementById('searchBtn');
      const qInput = document.getElementById('q');
      const autoFields = Array.from(document.querySelectorAll('[data-auto-submit="1"]'));
      const toggles = Array.from(document.querySelectorAll('.filter-toggle'));
      const isMobile = window.matchMedia && window.matchMedia('(max-width: 980px)').matches;

      function setSectionOpen(section, open) {
        if (!section) return;
        section.classList.toggle('is-open', open);
        const btn = section.querySelector('.filter-toggle');
        btn?.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      document.querySelectorAll('.filter-section').forEach(section => {
        setSectionOpen(section, !isMobile);
      });

      toggles.forEach(btn => {
        btn.addEventListener('click', () => {
          const section = btn.closest('.filter-section');
          if (!section) return;
          const open = !section.classList.contains('is-open');
          setSectionOpen(section, open);
        });
      });

      const updateSearchBtn = () => {
        if (!searchBtn || !qInput) return;
        const hasText = qInput.value.trim().length > 0;
        searchBtn.disabled = !hasText;
      };
      updateSearchBtn();
      qInput?.addEventListener('input', updateSearchBtn);
      qInput?.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        form?.submit();
      });
      searchBtn?.addEventListener('click', () => {
        if (qInput && qInput.value.trim().length > 0) {
          form?.submit();
        } else {
          qInput?.focus();
        }
      });

      autoFields.forEach(field => {
        field.addEventListener('change', () => { form?.submit(); });
      });

      const fmt = (n) => (n || 0).toLocaleString('ko-KR') + '원';
      const rmin = document.getElementById('range_min');
      const rmax = document.getElementById('range_max');
      const bmin = document.getElementById('bubble_min');
      const bmax = document.getElementById('bubble_max');

      function pct(input){
        const min = parseInt(input.min||'0',10), max = parseInt(input.max||'100',10);
        const val = parseInt(input.value||'0',10);
        return Math.min(100, Math.max(0, ((val-min)/(max-min))*100));
      }

      function positionBubble(input, bubble){
        if (!bubble) return;
        const field = bubble.parentElement;
        const rectW = field.clientWidth || 0;
        const half = (bubble.offsetWidth||40)/2;
        const padL = 0;
        const p = pct(input)/100;
        let x = padL + p * rectW;
        const minX = half; const maxX = rectW - half;
        x = Math.min(maxX, Math.max(minX, x));
        bubble.style.left = x + 'px';
        input.style.setProperty('--p', (p*100)+'%');
      }

      function syncBudget(e) {
        if (!rmin || !rmax) return;
        let a = parseInt(rmin.value || '0', 10);
        let b = parseInt(rmax.value || '0', 10);
        if (a > b) {
          if (e && e.target === rmin) rmax.value = a; else rmin.value = b;
          a = parseInt(rmin.value, 10); b = parseInt(rmax.value, 10);
        }
        if (bmin) { bmin.textContent = fmt(a); positionBubble(rmin, bmin); }
        if (bmax) { bmax.textContent = fmt(b); positionBubble(rmax, bmax); }
        if (e && e.type === 'change') {
          form?.submit();
        }
      }
      ['input','change'].forEach(ev=>{ rmin?.addEventListener(ev, syncBudget); rmax?.addEventListener(ev, syncBudget); });
      syncBudget();

      const disciplineSelect = document.getElementById('discipline');
      const groups = Array.from(document.querySelectorAll('[data-filter-group]'));
      const genreSelect = document.getElementById('genre');
      function applyFilters(){
        const value = disciplineSelect?.value || 'all';
        groups.forEach(group => {
          const key = group.dataset.filterGroup;
          if (value === 'all') {
            group.style.display = '';
            return;
          }
          group.style.display = key === value ? '' : 'none';
        });
        if (genreSelect) {
          const options = Array.from(genreSelect.options);
          let hasVisible = false;
          options.forEach(opt => {
            const optDisc = opt.dataset.discipline || 'all';
            const show = value === 'all' || optDisc === 'all' || optDisc === value;
            opt.hidden = !show;
            if (show && opt.value) hasVisible = true;
          });
          if (genreSelect.value && genreSelect.selectedOptions[0]?.hidden) {
            genreSelect.value = '';
          }
          if (!hasVisible) {
            genreSelect.value = '';
          }
        }
      }
      disciplineSelect?.addEventListener('change', applyFilters);
      applyFilters();

      const scrollKey = 'enc_artist_scroll';
      document.querySelectorAll('[data-scroll-save="1"]').forEach(el => {
        el.addEventListener('click', () => {
          try { sessionStorage.setItem(scrollKey, String(window.scrollY)); } catch (e) {}
        });
      });
      try {
        const saved = sessionStorage.getItem(scrollKey);
        if (saved) {
          sessionStorage.removeItem(scrollKey);
          requestAnimationFrame(() => window.scrollTo(0, parseInt(saved, 10) || 0));
        }
      } catch (e) {}
    })();
  </script>
</body>
</html>
