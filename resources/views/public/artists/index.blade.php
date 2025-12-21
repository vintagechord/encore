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

    .filter-form { display: grid; gap: 18px; }
    .filter-quick { display: grid; gap: 10px; grid-template-columns: repeat(6, minmax(0, 1fr)); }
    .filter-quick .field { display: grid; gap: 6px; }
    .filter-quick label { font-size: 12px; color: var(--muted); }
    .filter-quick input,
    .filter-quick select { width: 100%; height: 44px; padding: 0 12px; border-radius: 12px; border: 1px solid var(--border); background: var(--card); color: var(--fg); }
    .filter-quick .cta { display:flex; align-items: end; }
    .filter-quick .cta button { width: 100%; height: 44px; border-radius: 12px; border: 1px solid var(--btn); background: var(--btn); color: var(--btn-text); font-weight: 700; cursor: pointer; }
    .filter-quick .cta button:hover { background: var(--btn-hover); border-color: var(--btn-hover); }

    .explore-layout { display: grid; gap: 20px; grid-template-columns: 260px 1fr; align-items: start; }
    .filter-panel { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 16px; position: sticky; top: 118px; }
    .filter-panel h2 { margin: 0 0 12px; font-size: 16px; }
    .filter-block { display: grid; gap: 8px; margin-bottom: 16px; }
    .filter-block label { font-size: 12px; color: var(--muted); }
    .filter-block input,
    .filter-block select { width: 100%; height: 42px; padding: 0 10px; border-radius: 10px; border: 1px solid var(--border); background: var(--card-alt); color: var(--fg); }
    .filter-block .chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .filter-block .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border); background: rgba(141,31,45,.1); font-size: 12px; color: var(--fg); text-decoration: none; }

    .results-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .results-count { color: var(--muted); font-size: 13px; }
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

    .empty-state { padding: 32px; border: 1px dashed var(--border); border-radius: 16px; color: var(--muted); text-align: center; }

    @media (max-width: 1200px) {
      .artist-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 980px) {
      .filter-quick { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .explore-layout { grid-template-columns: 1fr; }
      .filter-panel { position: static; }
      .artist-card { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .filter-quick { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .artist-actions { flex-direction: column; align-items: stretch; }
    }
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

    $fallbackGenres = [
      'music' => ['K-POP','발라드','트로트','힙합','인디','밴드','재즈/소울'],
      'mc' => ['전문 MC','아나운서','홈쇼핑','주례','돌잔치','기업행사'],
      'dance' => ['스트릿','K-POP','비보이','락킹','현대무용','발레'],
      'performance' => ['마술','서커스','LED','퍼포먼스'],
      'plan' => ['기획공연','쇼케이스','콜라보'],
      'celebrity' => ['셀럽','인플루언서','배우/방송인'],
    ];
  @endphp

  <main class="enc-container explore">
    <section class="explore-hero">
      <span class="explore-eyebrow">ARTIST SEARCH</span>
      <h1>가격 · 일정 · 지역 기준으로 아티스트를 바로 찾으세요</h1>
      <p>분야별 카테고리와 세부 필터로 실제 섭외에 필요한 조건만 빠르게 좁혀드립니다.</p>
    </section>

    <form class="filter-form" method="get" action="{{ route('artists.browse') }}">
      <div class="filter-quick">
        <div class="field">
          <label for="region">지역</label>
          <select id="region" name="region">
            <option value="">전체</option>
            @foreach(['서울','경기','인천','강원','대전','세종','충북','충남','광주','전북','전남','대구','경북','부산','울산','경남','제주'] as $region)
              <option value="{{ $region }}" @selected(($filters['region'] ?? '') === $region)>{{ $region }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label for="price">예상 금액대</label>
          <select id="price" name="price">
            <option value="">전체</option>
            <option value="0-2000000" @selected(($filters['price'] ?? '') === '0-2000000')>200만원 이하</option>
            <option value="2000000-5000000" @selected(($filters['price'] ?? '') === '2000000-5000000')>200만~500만원</option>
            <option value="5000000-10000000" @selected(($filters['price'] ?? '') === '5000000-10000000')>500만~1000만원</option>
            <option value="10000000+" @selected(($filters['price'] ?? '') === '10000000+')>1000만원 이상</option>
          </select>
        </div>
        <div class="field">
          <label for="date">일정</label>
          <input id="date" type="date" name="date" value="{{ $filters['date'] ?? '' }}">
        </div>
        <div class="field">
          <label for="discipline">분야</label>
          <select id="discipline" name="discipline">
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
          <button type="submit">검색</button>
        </div>
      </div>

      <div class="explore-layout">
        <aside class="filter-panel">
          <h2>상세 조건</h2>

          <div class="filter-block">
            <label for="min_price">최소 금액</label>
            <input id="min_price" name="min_price" inputmode="numeric" placeholder="예: 3000000" value="{{ $filters['min_price'] ?? '' }}">
          </div>
          <div class="filter-block">
            <label for="max_price">최대 금액</label>
            <input id="max_price" name="max_price" inputmode="numeric" placeholder="예: 8000000" value="{{ $filters['max_price'] ?? '' }}">
          </div>

          <div class="filter-block">
            <label for="age_group">연령대</label>
            <select id="age_group" name="age_group">
              <option value="">선택 안함</option>
              @foreach(['10대','20대','30대','40대','50대 이상'] as $age)
                <option value="{{ $age }}" @selected(($filters['age_group'] ?? '') === $age)>{{ $age }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="music">
            <label for="team_type">팀 구성</label>
            <select id="team_type" name="team_type">
              <option value="">선택 안함</option>
              @foreach(['솔로','팀','혼성','듀엣','밴드'] as $team)
                <option value="{{ $team }}" @selected(($filters['team_type'] ?? '') === $team)>{{ $team }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="music">
            <label for="genre">장르</label>
            <select id="genre" name="genre">
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
            <select id="career" name="career">
              <option value="">선택 안함</option>
              @foreach(['1년 미만','1~3년','3~7년','7년 이상'] as $career)
                <option value="{{ $career }}" @selected(($filters['career'] ?? '') === $career)>{{ $career }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="mc">
            <label for="mc_type">사회자 분류</label>
            <select id="mc_type" name="mc_type">
              <option value="">선택 안함</option>
              @foreach(['전문MC','아나운서','홈쇼핑','주례','돌잔치','기업행사'] as $mc)
                <option value="{{ $mc }}" @selected(($filters['mc_type'] ?? '') === $mc)>{{ $mc }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="mc">
            <label for="mc_style">진행 스타일</label>
            <select id="mc_style" name="mc_style">
              <option value="">선택 안함</option>
              @foreach(['차분한','활발한','밝은','차분+유쾌'] as $style)
                <option value="{{ $style }}" @selected(($filters['mc_style'] ?? '') === $style)>{{ $style }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block" data-filter-group="dance">
            <label for="dance_style">댄스 스타일</label>
            <select id="dance_style" name="dance_style">
              <option value="">선택 안함</option>
              @foreach(['스트릿','K-POP','비보이','락킹','현대무용','퍼포먼스'] as $style)
                <option value="{{ $style }}" @selected(($filters['dance_style'] ?? '') === $style)>{{ $style }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-block">
            <label>빠른 필터</label>
            <div class="chips">
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'music'])) }}">음악</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'mc'])) }}">사회(MC)</a>
              <a class="chip" href="{{ route('artists.browse', array_merge(request()->except('discipline'), ['discipline' => 'dance'])) }}">댄스</a>
              <a class="chip" href="{{ route('artists.browse') }}">전체 해제</a>
            </div>
          </div>
        </aside>

        <section>
          <div class="results-head">
            <div class="results-count">총 {{ $artists->total() }}팀</div>
            <div class="results-count">{{ $activeLabel }}</div>
          </div>

          <div class="artist-grid">
            @forelse($artists as $artist)
              @php
                $img = $artist->image_url ?? ($artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image ?? null));
                $intro = $artist->bio ?? data_get($artist->meta ?? [], 'intro') ?? $artist->notes ?? '';
                $feeMin = $artist->fee_min ?? $artist->min_fee ?? null;
                $feeMax = $artist->fee_max ?? $artist->max_fee ?? null;
                $disciplineName = $artist->discipline->name ?? null;
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
                    <a class="btn ghost" href="{{ route('artist.show', ['artist' => $artist->id]) }}">필모/소개</a>
                    <a class="btn" href="{{ route('direct.request.create', ['requested' => $artist->name ?? $artist->stage_name]) }}">지정섭외</a>
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
    })();
  </script>
</body>
</html>
