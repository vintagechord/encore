<!doctype html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>추천안 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="color-scheme" content="dark light">
  <meta name="theme-color" content="#0b090a" media="(prefers-color-scheme: dark)">
  <meta name="theme-color" content="#fff7e6" media="(prefers-color-scheme: light)">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
  <style>
    /* Use global tokens from shared header; define only page-specific ones. */
    :root {
      --tag-bg: rgba(141, 31, 45, 0.12);
      --tag-border: rgba(141, 31, 45, 0.35);
      --tag-text: #f5e2b2;
    }

    [data-theme="light"] {
      --tag-bg: rgba(122, 30, 46, 0.12);
      --tag-border: rgba(122, 30, 46, 0.28);
      --tag-text: #7a1e2e;
    }

    * {
      box-sizing: border-box
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      font-family: var(--font-sans, "Noto Sans KR", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif);
      color: var(--fg);
      background: var(--bg);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    main {
      flex: 1
    }

    a { color: inherit; text-decoration: none }
    a:hover { color: var(--accent-hover); text-decoration: underline }

    /* 헤더/내비게이션은 공통 partial 사용. 페이지 상단 전용 스타일은 제거 */

    .container {
      max-width: 1120px;
      margin: 0 auto;
      padding: 24px
    }

    .meta {
      color: var(--muted);
      font-size: 13px
    }

    h1 {
      margin: 6px 0 4px;
      font-size: 22px;
      letter-spacing: -0.01em
    }

    h2 {
      margin: 18px 0 8px;
      font-size: 18px
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 12px;
      margin-top: 12px
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
    }

    .card .row {
      display: flex;
      gap: 12px;
      align-items: center;
      flex-wrap: wrap
    }

    .badge {
      display: inline-flex;
      align-items: center;
      font-size: 12px;
      border-radius: 999px;
      padding: 2px 8px;
      border: 1px solid var(--chip-border);
      background: var(--chip);
      color: var(--fg);
    }

    .tag {
      display: inline-flex;
      align-items: center;
      font-size: 12px;
      border: 1px solid var(--tag-border);
      background: var(--tag-bg);
      color: var(--tag-text);
      border-radius: 999px;
      padding: 2px 8px
    }

    .muted {
      color: var(--muted)
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 1px solid var(--border);
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 13px;
      background: var(--card-alt);
    }

    /* 이미지 썸네일 레이아웃 */
    .card.media {
      flex-direction: row;
      align-items: flex-start;
      gap: 12px
    }

    .card-figure {
      flex: 0 0 auto
    }

    .thumb {
      display: block;
      width: 140px;
      height: 96px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: #0d1628;
    }
    [data-theme="light"] .thumb { background: #eef2f9; }

    .card.media .content {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      gap: 8px
    }

    /* 상단 고정 연락 버튼바 */
    .contact-top {
      position: sticky;
      top: 0;
      z-index: 25;
      transform: translateY(-100%);
      opacity: 0;
      pointer-events: none;
      transition: transform .25s ease, opacity .25s ease;
      background: rgba(7, 12, 24, 0.95);
      color: var(--fg);
      border-bottom: 1px solid var(--border);
    }
    [data-theme="light"] .contact-top { background: rgba(255, 255, 255, 0.94); }

    .contact-top.show {
      transform: translateY(0);
      opacity: 1;
      pointer-events: auto;
    }

    .contact-top .inner {
      max-width: 1120px;
      margin: 0 auto;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px
    }

    .contact-top .title {
      font-weight: 700;
      font-size: 14px
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 14px;
      border-radius: 12px;
      border: 1px solid var(--chip-border);
      background: var(--card-alt);
      color: var(--fg);
      font-weight: 700;
      cursor: pointer;
      transition: background .2s ease, border-color .2s ease, color .2s ease;
    }

    .btn:hover {
      border-color: var(--accent);
      color: var(--accent-hover);
    }

    .btn-primary {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent);
    }

    .btn-primary:hover {
      background: var(--accent-hover);
      border-color: var(--accent-hover);
      color: #fff;
    }

    .btn-ghost {
      background: transparent;
      color: var(--fg);
      border-color: var(--chip-border);
    }

    .btn-ghost:hover {
      background: rgba(99, 102, 241, .12);
      color: var(--accent-hover);
    }

    /* 하단 고정 플로팅 액션 */
    .contact-fab {
      position: fixed;
      left: 0;
      right: 0;
      bottom: 10px;
      display: flex;
      justify-content: center;
      z-index: 30;
      opacity: 0;
      pointer-events: none;
      transform: translateY(10px);
      transition: opacity .25s ease, transform .25s ease;
    }

    .contact-fab.show {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0);
    }

    .fab-inner {
      display: inline-flex;
      gap: 10px;
      background: rgba(7, 12, 24, 0.95);
      color: var(--fg);
      border: 1px solid var(--border);
      border-radius: 999px;
      padding: 8px;
      box-shadow: 0 12px 30px rgba(2, 4, 10, .55);
    }
    [data-theme="light"] .fab-inner { background: rgba(255, 255, 255, 0.95); box-shadow: 0 12px 30px rgba(2, 6, 23, .1); }

    .fab-inner .btn {
      border-color: var(--chip-border)
    }

    .fab-inner .btn:hover {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent-hover);
    }

    /* ▼ 요약 박스 스타일 */
    .summary-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 8px
    }

    .chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 1px solid var(--border);
      background: var(--card-alt);
      color: var(--fg);
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 13px;
      white-space: nowrap;
    }

    .chip .ico {
      display: inline-flex;
      width: 16px;
      height: 16px
    }

    .price-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 10px;
      background: rgba(99, 102, 241, 0.18);
      border: 1px solid rgba(99, 102, 241, 0.45);
      color: var(--fg);
      font-weight: 600;
      font-size: 14px;
    }

    .meta-list {
      list-style: none;
      padding: 0;
      margin: 8px 0 0;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 6px 12px;
    }

    .meta-list li {
      display: flex;
      flex-direction: column;
      gap: 2px;
      padding: 8px 10px;
      border: 1px solid var(--chip-border);
      border-radius: 8px;
      background: var(--card-alt);
    }

    .meta-label {
      font-size: 11px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .meta-value {
      font-weight: 600;
      font-size: 14px;
    }

    .tag-list {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      margin: 4px 0 0;
    }

    .tag-pill {
      display: inline-flex;
      align-items: center;
      padding: 4px 8px;
      border-radius: 999px;
      background: rgba(99, 102, 241, 0.18);
      border: 1px solid var(--tag-border);
      font-size: 12px;
      color: var(--tag-text);
    }

    .card-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 8px;
    }

    .btn-outline {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
    }

    .btn-outline:hover {
      background: var(--accent);
      border-color: var(--accent-hover);
      color: #fff;
    }

    /* ===== 인쇄 최적화 ===== */
    @media print {
      @page {
        size: A4;
        margin: 16mm;
      }

      :root {
        --bg: #fff;
        --fg: #000;
        --muted: #333;
        --border: #000;
      }

      body {
        background: #fff !important;
        color: #000 !important;
      }

      a {
        color: #000 !important;
        text-decoration: none !important;
      }

      header,
      .contact-top,
      .contact-fab,
      footer,
      .btn,
      .pill,
      .nav a.pill,
      .no-print {
        display: none !important;
      }

      .container {
        padding: 0 !important;
      }

      .grid {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
      }

      .card {
        border-color: #000 !important;
        box-shadow: none !important;
        break-inside: avoid;
        page-break-inside: avoid;
      }

      .badge,
      .tag,
      .chip,
      .tag-pill,
      .price-badge,
      .meta-list li {
        border-color: #000 !important;
        background: #fff !important;
        color: #000 !important;
      }

      .thumb {
        border-color: #000 !important;
      }

      h1,
      h2,
      h3,
      .meta,
      .muted {
        color: #000 !important;
      }

      body {
        counter-reset: rec 0;
      }

      .grid[role="list"]>article.card h3::before {
        counter-increment: rec;
        content: counter(rec) ". ";
        font-weight: 700;
      }
    }
  </style>
</head>

<body>
  @include('public.partials.header')

  <div class="page-head">
    <div class="contact-top" id="contactTop" role="region" aria-label="빠른 연락 배너">
      <div class="inner">
        <span class="title">추천안이 도움이 되셨나요?</span>
        <div style="display:flex;gap:8px;align-items:center">
          <a class="btn btn-ghost" href="{{ url('/') }}">홈</a>
          <a class="btn btn-primary" href="{{ route('inquiry.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16v16H4z" />
              <path d="M22 6l-10 7L2 6" />
            </svg>
            연락하기
          </a>
        </div>
      </div>
    </div>
  </div>

  <main id="main">
    <div class="container">
      <header class="card" style="margin-bottom:12px" aria-labelledby="rec-title">
        <div class="row">
          <h1 id="rec-title">{{ $set->label ?? '추천안' }}</h1>
          @if(!empty($set->sent_at))
          <span class="badge" title="보낸 시각(UTC)">보냄 {{ optional($set->sent_at)->timezone('UTC')->format('Y-m-d H:i') }} <span class="muted">(UTC)</span></span>
          @endif
        </div>
        @if(isset($intake))
        <div class="meta">
          요청자: <strong>{{ $intake->contact_name }}</strong>
          <span class="muted">({{ $intake->contact_email }})</span>
          <span class="muted">· 접수 {{ optional($intake->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
        </div>
        @endif
      </header>

      {{-- ▼ 요약 데이터 계산 --}}
      @php
      $artists = isset($set) && method_exists($set, 'artists') ? $set->artists : collect();
      $hasArtists = $artists && $artists->count() > 0;
      $items = is_array($set->items ?? null) ? $set->items : (is_string($set->items ?? null) ? json_decode($set->items, true) : []);
      $count = $hasArtists ? $artists->count() : (is_array($items) ? count($items) : 0);
      $budget = $set->total_cost ?? null;
      $genres = [];
      $period = null;

      if ($hasArtists) {
      foreach ($artists as $a) {
      if (!empty($a->genre)) {
      $genres[] = is_array($a->genre) ? implode(', ', array_filter($a->genre)) : $a->genre;
      }
      }
      }

      if (!empty($items) && is_array($items)) {
      foreach ($items as $it) {
      $meta = $it['meta'] ?? [];
      if (is_array($meta)) {
      foreach ($meta as $k => $v) {
      $lk = is_string($k) ? mb_strtolower($k, 'UTF-8') : (string)$k;
      if (strpos($lk, 'genre') !== false || strpos($lk, '장르') !== false) {
      if (is_array($v)) {
      foreach ($v as $vv) {
      $genres[] = (string)$vv;
      }
      } else {
      $genres[] = (string)$v;
      }
      }
      if (strpos($lk, '기간') !== false || strpos($lk, '일정') !== false || strpos($lk, 'date') !== false || strpos($lk, 'range') !== false || strpos($lk, '행사일') !== false) {
      if (!$period) {
      if (is_array($v)) {
      $vals = array_values(array_filter(array_map(fn($x) => is_scalar($x) ? (string)$x : json_encode($x, JSON_UNESCAPED_UNICODE), $v)));
      $period = count($vals) >= 2 ? ($vals[0] . ' ~ ' . $vals[count($vals) - 1]) : implode(', ', $vals);
      } else {
      $period = (string)$v;
      }
      }
      }
      if ($budget === null && (strpos($lk, '예산') !== false || strpos($lk, '금액') !== false || strpos($lk, '비용') !== false || strpos($lk, 'budget') !== false || strpos($lk, 'cost') !== false || strpos($lk, 'total') !== false)) {
      $budget = $v;
      }
      }
      }
      }
      }

      if (!$period && isset($intake) && ($intake->event_start || $intake->event_end)) {
      $start = optional($intake->event_start)->format('Y-m-d');
      $end = optional($intake->event_end)->format('Y-m-d');
      if ($start && $end && $start !== $end) {
      $period = $start . ' ~ ' . $end;
      } else {
      $period = $start ?: $end ?: $period;
      }
      }

      $genres = array_values(array_unique(array_filter(array_map('strval', $genres))));
      $genresLabel = $genres ? implode(', ', array_slice($genres, 0, 3)) . (count($genres) > 3 ? ' 외' : '') : null;

      $budgetLabel = null;
      if (is_numeric($budget)) {
      $budgetLabel = number_format((float)$budget) . ' 원';
      } elseif (is_string($budget) && trim($budget) !== '') {
      $budgetLabel = $budget;
      }

      $cityLabel = (isset($intake) && $intake->city) ? $intake->city : null;
      $audienceLabel = (isset($intake) && $intake->audience_size) ? number_format((int)$intake->audience_size) . '명' : null;
      $performanceLabel = (isset($intake) && $intake->performance_type) ? $intake->performance_type : null;
      $setDurationLabel = (isset($intake) && $intake->set_duration) ? $intake->set_duration . '분' : null;
      $setsCountLabel = (isset($intake) && $intake->sets_count) ? $intake->sets_count . '회' : null;
      $eventFlexLabel = (isset($intake) && $intake->date_flexible) ? '일정 조율 가능' : null;
      $requestedArtistName = (isset($intake) && $intake->requested_artist_name) ? $intake->requested_artist_name : null;
      $venueTypeLabel = (isset($intake) && $intake->venue_type) ? $intake->venue_type : null;
      $indoorLabel = (isset($intake) && $intake->indoor_outdoor) ? $intake->indoor_outdoor : null;
      $notesCopy = (isset($intake) && $intake->notes) ? $intake->notes : null;

      $eventDetails = [];
      if ($performanceLabel) $eventDetails['공연 형태'] = $performanceLabel;
      if ($setDurationLabel) $eventDetails['세트 길이'] = $setDurationLabel;
      if ($setsCountLabel) $eventDetails['진행 횟수'] = $setsCountLabel;
      if ($audienceLabel) $eventDetails['예상 관객'] = $audienceLabel;
      if ($venueTypeLabel) $eventDetails['장소 유형'] = $venueTypeLabel;
      if ($indoorLabel) $eventDetails['실내/야외'] = $indoorLabel;
      if ($requestedArtistName) $eventDetails['선호 아티스트'] = $requestedArtistName;
      if ($cityLabel) $eventDetails['지역'] = $cityLabel;

      $hasSummary = ($budgetLabel || $genresLabel || $period || $count || $cityLabel || $audienceLabel || $performanceLabel || $setDurationLabel || $setsCountLabel || $eventFlexLabel);
      @endphp

      @if($hasSummary)
      <section class="card" aria-labelledby="summary-title" style="margin-bottom:12px">
        <h2 id="summary-title" style="margin:0 0 6px">요약</h2>
        <div class="summary-grid" role="list">
          @if($budgetLabel)
          <div class="chip" role="listitem" aria-label="예산 {{ $budgetLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
              </svg></span><strong>예산</strong> {{ $budgetLabel }}</div>
          @endif
          @if($genresLabel)
          <div class="chip" role="listitem" aria-label="장르 {{ $genresLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M9 18V5l12-2v13" />
                <circle cx="6" cy="18" r="3" />
              </svg></span><strong>장르</strong> {{ $genresLabel }}</div>
          @endif
          @if($period)
          <div class="chip" role="listitem" aria-label="기간 {{ $period }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg></span><strong>기간</strong> {{ $period }}</div>
          @endif
          @if($count)
          <div class="chip" role="listitem" aria-label="후보 {{ $count }}개"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg></span><strong>후보</strong> {{ $count }}개</div>
          @endif
          @if($cityLabel)
          <div class="chip" role="listitem" aria-label="지역 {{ $cityLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M12 21s6-5.686 6-10a6 6 0 0 0-12 0c0 4.314 6 10 6 10z" />
                <circle cx="12" cy="11" r="2.5" />
              </svg></span><strong>지역</strong> {{ $cityLabel }}</div>
          @endif
          @if($audienceLabel)
          <div class="chip" role="listitem" aria-label="예상 관객 {{ $audienceLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M7 21v-2a4 4 0 0 1 4-4h2" />
                <circle cx="9" cy="7" r="3.5" />
                <path d="M17 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M15 3.13a4 4 0 0 1 0 7.75" />
              </svg></span><strong>관객</strong> {{ $audienceLabel }}</div>
          @endif
          @if($performanceLabel)
          <div class="chip" role="listitem" aria-label="공연 형태 {{ $performanceLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M9 18V5l12-2v11" />
                <circle cx="6" cy="18" r="3" />
              </svg></span><strong>형태</strong> {{ $performanceLabel }}</div>
          @endif
          @if($setDurationLabel)
          <div class="chip" role="listitem" aria-label="세트 길이 {{ $setDurationLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v6l3 2" />
              </svg></span><strong>세트 길이</strong> {{ $setDurationLabel }}</div>
          @endif
          @if($setsCountLabel)
          <div class="chip" role="listitem" aria-label="진행 {{ $setsCountLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <path d="M4 7h16M4 12h16M4 17h16" />
                <path d="M8 7v10" />
              </svg></span><strong>세트 수</strong> {{ $setsCountLabel }}</div>
          @endif
          @if($eventFlexLabel)
          <div class="chip" role="listitem" aria-label="{{ $eventFlexLabel }}"><span class="ico" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <path d="M9 14l2 2 4-4" />
              </svg></span><strong>일정</strong> {{ $eventFlexLabel }}</div>
          @endif
        </div>
      </section>
      @endif

      @if((!empty($eventDetails) && count($eventDetails)) || $notesCopy)
      <section class="card" aria-labelledby="event-context-title" style="margin-bottom:12px">
        <h2 id="event-context-title" style="margin:0 0 6px">행사 정보</h2>
        @if(!empty($eventDetails) && count($eventDetails))
        <ul class="meta-list">
          @foreach($eventDetails as $label => $value)
          <li><span class="meta-label">{{ $label }}</span><span class="meta-value">{{ $value }}</span></li>
          @endforeach
        </ul>
        @endif
        @if($notesCopy)
        <div class="meta" style="margin-top:10px;white-space:pre-wrap">{{ $notesCopy }}</div>
        @endif
      </section>
      @endif

      @php /* 본문 */ @endphp
      @if($hasArtists)
      <section aria-labelledby="artist-list-title">
        <h2 id="artist-list-title">추천 아티스트</h2>
        <div class="grid" role="list">
          @foreach($artists as $a)
          @php
          $a_img = $a->thumbnail_url ?? $a->image_url ?? $a->photo_url ?? $a->cover_url ?? $a->cover ?? $a->image ?? (is_array($a->links ?? null) ? ($a->links['image'] ?? null) : null);
          $rank = $a->pivot->rank ?? $loop->iteration;
          $reason = trim((string)($a->pivot->reason ?? ''));
          $score = data_get($a, 'pivot.score');
          $scoreLabel = is_numeric($score) ? number_format((float)$score, 1) : null;
          $feeRange = $a->fee_range ?? null;
          $minFee = data_get($a, 'pivot.quoted_min', $feeRange['min'] ?? ($a->min_fee ?? $a->fee_min ?? null));
          $maxFee = data_get($a, 'pivot.quoted_max', $feeRange['max'] ?? ($a->max_fee ?? $a->fee_max ?? null));
          $currency = $feeRange['currency'] ?? 'KRW';
          $priceLabel = null;
          if (is_numeric($minFee) && (float)$minFee > 0) {
          $fmtMin = number_format((float)$minFee);
          if (is_numeric($maxFee) && (float)$maxFee > 0 && (float)$maxFee !== (float)$minFee) {
          $fmtMax = number_format((float)$maxFee);
          $priceLabel = $fmtMin.' ~ '.$fmtMax;
          } else {
          $priceLabel = $fmtMin;
          }
          $priceLabel .= $currency === 'KRW' ? ' 원' : (' '.$currency);
          } elseif (is_string($minFee) && trim($minFee) !== '') {
          $priceLabel = $minFee;
          }
          $tagList = [];
          foreach ((array)($a->genres ?? []) as $tag) {
          if (is_string($tag) && trim($tag) !== '') $tagList[] = trim($tag);
          }
          if (!$tagList && !empty($a->genre) && is_string($a->genre)) {
          foreach (preg_split('/[,;\\s]+/u', $a->genre) as $tag) {
          $tag = trim($tag);
          if ($tag !== '') $tagList[] = $tag;
          }
          }
          foreach ((array)($a->moods ?? []) as $tag) {
          if (is_string($tag) && trim($tag) !== '') $tagList[] = trim($tag);
          }
          $tagList = array_values(array_unique($tagList));
          $metaParts = [];
          if (!empty($a->formats)) {
          $metaParts[] = '구성: '.(is_array($a->formats) ? implode(', ', array_filter($a->formats)) : $a->formats);
          }
          if (!empty($a->home_city)) {
          $metaParts[] = '기반: '.$a->home_city;
          }
          if (data_get($a, 'pivot.fixed')) {
          $metaParts[] = '고정 추천';
          }
          if (data_get($a, 'pivot.excluded')) {
          $metaParts[] = '참고: 제외 후보';
          }
          $ctaUrl = route('inquiry.create', ['requested_artist_name' => $a->name]);
          @endphp
          <article class="card {{ $a_img ? 'media' : '' }}" role="listitem" aria-labelledby="a-{{ $a->id }}-name">
            @if($a_img)
            <figure class="card-figure">
              <img class="thumb" src="{{ $a_img }}" alt="{{ $a->name }} 썸네일" loading="lazy" decoding="async" referrerpolicy="no-referrer">
            </figure>
            @endif
            <div class="content">
              <div class="row">
                <span class="tag" aria-label="순위">#{{ $rank }}</span>
                <h3 id="a-{{ $a->id }}-name" style="margin:0">{{ $a->name }}</h3>
                @if($scoreLabel)<span class="badge" title="적합도 점수">{{ $scoreLabel }}</span>@endif
              </div>
              @if($priceLabel)
              <span class="price-badge" aria-label="예상 출연료 {{ $priceLabel }}">{{ $priceLabel }}</span>
              @endif
              @if($reason)
              <p style="margin:4px 0 0">{{ $reason }}</p>
              @endif
              @if($tagList)
              <div class="tag-list" aria-label="특징 태그">
                @foreach($tagList as $tag)
                <span class="tag-pill">{{ $tag }}</span>
                @endforeach
              </div>
              @endif
              @if(!empty($metaParts))
              <div class="meta">{{ implode(' · ', $metaParts) }}</div>
              @endif
              @if(!empty($a->links))
              <div class="row">
                @foreach((array)$a->links as $label => $url)
                @if(is_string($url) && $url && $label !== 'image')
                <a class="badge" href="{{ $url }}" target="_blank" rel="noopener">링크: {{ is_string($label)? $label : '바로가기' }}</a>
                @endif
                @endforeach
              </div>
              @endif
              <div class="card-actions">
                <a class="btn btn-outline" href="{{ $ctaUrl }}" aria-label="{{ $a->name }} 요청하기">이 아티스트 요청하기</a>
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </section>
      @elseif(!empty($items))
      <section aria-labelledby="alt-list-title">
        <h2 id="alt-list-title">추천 항목</h2>
        <div class="grid" role="list">
          @foreach($items as $idx => $it)
          @php
          $title = $it['title'] ?? $it['name'] ?? ('항목 '.($idx+1));
          $reason = trim((string)($it['reason'] ?? $it['desc'] ?? ''));
          $meta = is_array($it['meta'] ?? null) ? $it['meta'] : [];
          $i_img = $it['image'] ?? $it['thumbnail'] ?? $it['thumb'] ?? $it['img']
          ?? ($meta['image'] ?? $meta['thumbnail'] ?? $meta['thumb'] ?? $meta['img'] ?? null);
          $rank = $it['rank'] ?? ($idx + 1);
          $score = $it['score'] ?? ($meta['score'] ?? null);
          $scoreLabel = is_numeric($score) ? number_format((float)$score, 1) : null;
          $minFee = data_get($it, 'quoted_min') ?? data_get($it, 'min') ?? data_get($it, 'est_fee') ?? data_get($meta, 'quoted_min') ?? data_get($meta, 'min');
          $maxFee = data_get($it, 'quoted_max') ?? data_get($it, 'max') ?? data_get($meta, 'quoted_max') ?? data_get($meta, 'max');
          $currency = data_get($meta, 'currency', 'KRW');
          $priceLabel = null;
          if (is_numeric($minFee) && (float)$minFee > 0) {
          $fmtMin = number_format((float)$minFee);
          if (is_numeric($maxFee) && (float)$maxFee > 0 && (float)$maxFee !== (float)$minFee) {
          $fmtMax = number_format((float)$maxFee);
          $priceLabel = $fmtMin.' ~ '.$fmtMax;
          } else {
          $priceLabel = $fmtMin;
          }
          $priceLabel .= $currency === 'KRW' ? ' 원' : (' '.$currency);
          } elseif (is_string($minFee) && trim($minFee) !== '') {
          $priceLabel = $minFee;
          }
          $tagList = [];
          foreach (['genres','genre','tags','styles','moods'] as $key) {
          $val = $it[$key] ?? ($meta[$key] ?? null);
          if (is_array($val)) {
          foreach ($val as $tag) {
          if (is_string($tag) && trim($tag) !== '') $tagList[] = trim($tag);
          }
          } elseif (is_string($val) && trim($val) !== '') {
          foreach (preg_split('/[,;\\s]+/u', $val) as $tag) {
          $tag = trim($tag);
          if ($tag !== '') $tagList[] = $tag;
          }
          }
          }
          $tagList = array_values(array_unique($tagList));
          $metaParts = [];
          foreach ($meta as $k=>$v) {
          if (in_array($k, ['image','thumbnail','thumb','img','reason','score','quoted_min','quoted_max','min','max','currency'], true)) continue;
          $label = str_replace('_', ' ', (string)$k);
          if (is_array($v)) {
          $metaParts[] = $label.': '.implode(', ', array_map('strval', array_filter($v)));
          } else {
          $val = trim((string)$v);
          if ($val !== '') $metaParts[] = $label.': '.$val;
          }
          }
          if (!empty($it['fixed'])) $metaParts[] = '고정 추천';
          if (!empty($it['excluded'])) $metaParts[] = '제외 후보';
          $ctaUrl = route('inquiry.create', ['requested_artist_name' => $title]);
          @endphp
          <article class="card {{ $i_img ? 'media' : '' }}" role="listitem" aria-labelledby="i-{{ $idx }}-title">
            @if($i_img)
            <figure class="card-figure">
              <img class="thumb" src="{{ $i_img }}" alt="{{ $title }} 썸네일" loading="lazy" decoding="async" referrerpolicy="no-referrer">
            </figure>
            @endif
            <div class="content">
              <div class="row">
                <span class="tag">#{{ $rank }}</span>
                <h3 id="i-{{ $idx }}-title" style="margin:0">{{ $title }}</h3>
                @if($scoreLabel)<span class="badge" title="적합도 점수">{{ $scoreLabel }}</span>@endif
              </div>
              @if($priceLabel)
              <span class="price-badge" aria-label="예상 비용 {{ $priceLabel }}">{{ $priceLabel }}</span>
              @endif
              @if($reason)<p style="margin:4px 0 0">{{ $reason }}</p>@endif
              @if($tagList)
              <div class="tag-list" aria-label="특징 태그">
                @foreach($tagList as $tag)
                <span class="tag-pill">{{ $tag }}</span>
                @endforeach
              </div>
              @endif
              @if(!empty($metaParts))
              <div class="meta">{{ implode(' · ', $metaParts) }}</div>
              @endif
              <div class="card-actions">
                <a class="btn btn-outline" href="{{ $ctaUrl }}" aria-label="{{ $title }} 요청하기">이 구성으로 문의하기</a>
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </section>
      @else
      <section class="card" role="note" aria-live="polite">
        <p style="margin:0">추천 데이터를 준비 중입니다. 잠시 후 다시 확인해주세요.</p>
      </section>
      @endif

      @if(!empty($set->rationale))
      <section class="card" style="margin-top:12px" aria-labelledby="rationale-title">
        <h2 id="rationale-title">선정 기준</h2>
        <p style="margin:0;white-space:pre-wrap">{{ $set->rationale }}</p>
      </section>
      @endif

      @if(!empty($set->total_cost))
      <section class="card" style="margin-top:12px" aria-labelledby="budget-title">
        <h2 id="budget-title">예상 총비용</h2>
        <p style="margin:0"><strong>{{ number_format((float)$set->total_cost) }}</strong> 원 (부가 비용 별도일 수 있음)</p>
      </section>
      @endif

      <div id="sentinel" style="height:1px"></div>
    </div>
  </main>

  @include('public.partials.footer')

  <div class="contact-fab" id="contactFab" role="region" aria-label="빠른 연락 버튼">
    <div class="fab-inner">
      <a class="btn" href="{{ route('inquiry.create') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
        </svg>
        연락하기
      </a>
      <a class="btn btn-ghost" href="#top" id="toTopBtn" aria-label="맨 위로">위로</a>
    </div>
  </div>

  <script>
    (function() {
      document.getElementById('printBtn')?.addEventListener('click', function() {
        window.print();
      });

      const toTop = document.getElementById('toTopBtn');
      toTop?.addEventListener('click', function(e) {
        e.preventDefault();
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({
          top: 0,
          behavior: prefersReduced ? 'auto' : 'smooth'
        });
      });

      const topBar = document.getElementById('contactTop');
      const fab = document.getElementById('contactFab');
      const sentinel = document.getElementById('sentinel');

      function onScroll() {
        const y = window.scrollY || document.documentElement.scrollTop || 0;
        if (topBar) {
          topBar.classList.toggle('show', y > 160);
        }
        if (fab) {
          fab.classList.toggle('show', y > 280);
        }
      }
      window.addEventListener('scroll', onScroll, {
        passive: true
      });
      onScroll();

      if ('IntersectionObserver' in window && sentinel && fab) {
        const io = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              fab.style.opacity = '0.0';
              fab.style.pointerEvents = 'none';
            } else {
              fab.style.opacity = '';
              fab.style.pointerEvents = '';
            }
          });
        }, {
          rootMargin: '0px 0px -10% 0px'
        });
        io.observe(sentinel);
      }
    })();
  </script>
</body>

</html>
