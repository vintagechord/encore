{{-- Shared public header (matches home) --}}
<style id="enc-theme-vars">
  @import url('https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css');
  /* Global theme variables for all public pages */
  :root {
    --bg: radial-gradient(1200px 520px at 12% -8%, rgba(243, 198, 82, 0.18), rgba(10, 10, 10, 0) 60%),
      radial-gradient(900px 480px at 92% 2%, rgba(255, 255, 255, 0.05), rgba(10, 10, 10, 0) 55%),
      #0a0a0a;
    --fg: #f7f4ee;
    --muted: #b4b0a8;
    --accent: #f3c652;
    --accent-hover: #e6b940;
    --accent-2: #1a1a1a;
    --accent-text: #17120a;
    --ring: #f3c652;
    --btn: #f3c652;
    --btn-hover: #e6b940;
    --btn-text: #17120a;
    --danger: #8d1f2d;
    --danger-hover: #a12639;
    --danger-text: #ffffff;
    --card: #121212;
    --card-alt: #191919;
    --border: #2a2a2a;
    --chip: rgba(243, 198, 82, 0.16);
    --chip-border: rgba(243, 198, 82, 0.4);
    --font-sans: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
    --font-display: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
  }
  [data-theme="light"] {
    --bg: radial-gradient(980px 360px at 10% -6%, rgba(243, 198, 82, 0.14), rgba(255, 255, 255, 0) 60%),
      linear-gradient(180deg, #ffffff 0%, #f7f3ea 100%);
    --fg: #1c1b19;
    --muted: #6b655c;
    --accent: #f3c652;
    --accent-hover: #e6b940;
    --accent-2: #fdf8ef;
    --accent-text: #17120a;
    --ring: #e6b940;
    --btn: #f3c652;
    --btn-hover: #e6b940;
    --btn-text: #17120a;
    --danger: #8d1f2d;
    --danger-hover: #a12639;
    --danger-text: #ffffff;
    --card: #ffffff;
    --card-alt: #f7f3ea;
    --border: #e3ddd2;
    --chip: rgba(243, 198, 82, 0.2);
    --chip-border: rgba(243, 198, 82, 0.45);
  }
</style>

<style id="enc-shared-header-styles">
  header.enc-top { position: sticky; top:0; z-index:20; background: rgba(11,9,10,.88); backdrop-filter: saturate(200%) blur(12px); border-bottom:1px solid var(--border); }
  [data-theme="light"] header.enc-top { background: rgba(255,255,255,.85); backdrop-filter:saturate(180%) blur(10px); }
  body { font-family: var(--font-sans); background: var(--bg); color: var(--fg); }
  h1, h2, h3 { font-family: var(--font-display); letter-spacing: -0.01em; }
  .enc-container { max-width:1120px; margin:0 auto; padding:0 20px; }
  .enc-nav { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px 0; flex-wrap:wrap; }
  .enc-brand { display:flex; align-items:center; gap:10px; font-weight:700; color:inherit; text-decoration:none; }
  .enc-brand .brand-logo { height: 24px; width: auto; display:block; }
  @media (max-width: 768px) {
    .enc-brand .brand-logo { height: 20px; }
  }
  .enc-nav-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
  .enc-categorybar { border-top:1px solid var(--border); padding:8px 0 10px; position: relative; }
  .enc-categorylist { display:flex; align-items:center; gap:8px; overflow-x:auto; padding-bottom:2px; scrollbar-width:none; }
  .enc-categorylist::-webkit-scrollbar { display:none; }
  .enc-categorylink { display:inline-flex; align-items:center; justify-content:center; gap:6px; height:32px; padding:0 12px; border-radius:999px; border:1px solid transparent; color: var(--muted); text-decoration:none; font-weight:700; background: rgba(255,255,255,0.02); white-space:nowrap; cursor:pointer; }
  .enc-categorylink:hover { color: var(--fg); border-color: rgba(243,198,82,.4); background: rgba(243,198,82,.12); }
  .enc-categorylink.active { background: var(--accent); color: var(--accent-text); border-color: var(--accent); box-shadow: 0 6px 14px rgba(243,198,82,.25); }
  .enc-categorylink .chev { font-size: 12px; opacity: .7; }
  [data-theme="light"] .enc-categorylink { background: rgba(255,255,255,0.6); }
  [data-theme="light"] .enc-categorylink.active { background: var(--accent); color: var(--accent-text); }
  .enc-categorypanel { display:none; padding: 14px 0 6px; border-top: 1px solid var(--border); }
  .enc-categorypanel.open { display:block; }
  .enc-categorypanel .panel-grid { display:grid; gap:10px; grid-template-columns: 1fr; }
  .enc-categorypanel .panel-card { display:none; background: var(--card); border:1px solid var(--border); border-radius:14px; padding:12px 14px; }
  .enc-categorypanel .panel-card.active { display:block; }
  .enc-categorypanel .panel-title { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:10px; }
  .enc-categorypanel .panel-title strong { font-size:14px; }
  .enc-categorypanel .panel-title a { font-size:12px; color: var(--muted); text-decoration:none; }
  .enc-categorypanel .panel-title a:hover { color: var(--accent-hover); }
  .enc-categorypanel .panel-items { display:flex; flex-wrap:wrap; gap:6px; }
  .enc-categorypanel .panel-item { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; border:1px solid var(--border); background: var(--card-alt); color: var(--fg); text-decoration:none; font-size:12px; }
  .enc-categorypanel .panel-item:hover { border-color: var(--accent); color: var(--fg); box-shadow: 0 0 0 1px rgba(243,198,82,.25); }
  .enc-link { display:inline-flex; align-items:center; justify-content:center; height:36px; padding:0 12px; border-radius:999px; border:1px solid transparent; color: var(--muted); text-decoration:none; font-weight:600; }
  .enc-link:hover { background: rgba(243,198,82,.14); color: var(--fg); border-color: rgba(243,198,82,.35); }
  .enc-badge { display:inline-flex; align-items:center; font-size:12px; color:#1b130f; background: rgba(243,198,82,.85); border:1px solid rgba(243,198,82,.55); padding:2px 8px; border-radius:999px; }
  [data-theme="light"] .enc-badge { background: var(--accent-2); border-color: var(--accent-2); color:#fff; }
  .theme-toggle { display:inline-flex; align-items:center; justify-content:center; gap:8px; height:36px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); cursor:pointer; }
  [data-theme="light"] .theme-toggle { background:#ffffff; color:#1f2937; border-color:#d7dce2; }
  /* Mypage pill button (brand tone) */
  .enc-mypage{
    display:inline-flex; align-items:center; gap:6px;
    height: 36px; padding:0 12px; border-radius:999px;
    background: var(--btn); border:1px solid var(--btn); color:var(--btn-text); font-weight:700; text-decoration:none;
    box-shadow: 0 4px 12px rgba(243,198,82,.22);
    transition: transform .08s ease, background .2s ease, box-shadow .2s ease;
  }
  .enc-mypage:hover{ background: var(--btn-hover); border-color: var(--btn-hover); color:var(--btn-text); text-decoration:none; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(243,198,82,.3); }
  .enc-cart { position: relative; display:inline-flex; align-items:center; justify-content:center; height:36px; width:36px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); text-decoration:none; }
  .enc-cart:hover { border-color: var(--accent); box-shadow: 0 8px 18px rgba(243,198,82,.18); }
  .enc-cart svg { width:18px; height:18px; }
  .enc-cart .count { position:absolute; top:-6px; right:-6px; min-width:18px; height:18px; padding:0 5px; border-radius:999px; background: var(--accent); color: var(--accent-text); font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; }
  /* Date input: ensure calendar icon visible per theme (global) */
  [data-theme="dark"] input[type="date"] { color-scheme: dark !important; }
  [data-theme="light"] input[type="date"] { color-scheme: light !important; }
  /* WebKit/Blink */
  [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator,
  [data-theme="dark"] input::-webkit-calendar-picker-indicator {
    /* Force white calendar icon for dark theme */
    filter: invert(1) brightness(2.2) contrast(1.2) !important;
    opacity: 1 !important;
    background-color: transparent !important;
  }
  [data-theme="light"] input[type="date"]::-webkit-calendar-picker-indicator,
  [data-theme="light"] input::-webkit-calendar-picker-indicator { filter: none !important; opacity: 1; }
  /* As a fallback on some Chromium builds, draw our own white calendar glyph */
  @supports (-webkit-appearance: none) or (-moz-appearance: none) {
    [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
      background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='4' width='18' height='18' rx='2' ry='2'/><line x1='16' y1='2' x2='16' y2='6'/><line x1='8' y1='2' x2='8' y2='6'/><line x1='3' y1='10' x2='21' y2='10'/></svg>");
      background-repeat: no-repeat; background-position: center; background-size: 18px 18px;
    }
  }
  /* Firefox */
  [data-theme="dark"] input[type="date"]::-moz-calendar-picker-indicator { filter: invert(1) brightness(2) contrast(1.2) !important; }
  [data-theme="light"] input[type="date"]::-moz-calendar-picker-indicator { filter: none !important; }
</style>

<style id="enc-responsive">
  /* Global responsive defaults used across pages */
  @media (max-width: 1024px) {
    .enc-container { padding: 0 18px; }
    .enc-nav { padding: 14px 0; }
  }
  @media (max-width: 768px) {
    .enc-container { padding: 0 18px; }
    /* Ensure consistent mobile side padding across pages using different wrappers */
    .container, .page { padding-left: 18px !important; padding-right: 18px !important; }
    .enc-nav { gap: 12px; padding: 12px 0; }
    .enc-nav-right { gap: 6px; }
    .theme-toggle, .enc-link { height: 34px; padding: 0 10px; border-radius: 999px; }
    .enc-categorybar { padding: 6px 0 8px; }
    .enc-categorylink { height: 30px; padding: 0 10px; font-size: 12px; }
    .enc-categorypanel .panel-grid { grid-template-columns: 1fr; }
    .enc-categorypanel .panel-card { padding: 10px 12px; }
    .enc-cart { width: 34px; height: 34px; }
    .enc-cart .count { top:-5px; right:-5px; }

    /* Common components used throughout pages */
    .grid { grid-template-columns: 1fr !important; }
    .card.media { flex-direction: column !important; align-items: stretch !important; }
    .thumb, .thumb-wrap { width: 100% !important; max-width: 100% !important; height: auto; }
    .meta-list { grid-template-columns: 1fr !important; }
    .btn { padding: 8px 12px; border-radius: 10px; }
    .nav button { width: 36px; height: 36px; }
    .enc-footer-inner { padding: 18px 18px; }
  }
  @media (min-width: 769px) and (max-width: 1024px) {
    .thumb-wrap { max-width: 70vw; }
  }
</style>

@php
  $categoryLinks = $categoryLinks ?? [
    ['slug' => 'all', 'label' => '전체'],
    ['slug' => 'music', 'label' => '음악'],
    ['slug' => 'mc', 'label' => '사회(MC)'],
    ['slug' => 'dance', 'label' => '댄스'],
    ['slug' => 'performance', 'label' => '퍼포먼스'],
    ['slug' => 'plan', 'label' => '기획공연'],
    ['slug' => 'celebrity', 'label' => '셀럽'],
  ];
  $categorySubLinks = $categorySubLinks ?? [
    'music' => [
      ['label' => 'K-POP', 'query' => ['discipline' => 'music', 'genre' => 'K-POP']],
      ['label' => '발라드', 'query' => ['discipline' => 'music', 'genre' => '발라드']],
      ['label' => '트로트', 'query' => ['discipline' => 'music', 'genre' => '트로트']],
      ['label' => '힙합', 'query' => ['discipline' => 'music', 'genre' => '힙합']],
      ['label' => 'R&B/Soul', 'query' => ['discipline' => 'music', 'genre' => 'R&B/Soul']],
      ['label' => '인디', 'query' => ['discipline' => 'music', 'genre' => '인디']],
      ['label' => '밴드', 'query' => ['discipline' => 'music', 'genre' => '밴드']],
      ['label' => '재즈/소울', 'query' => ['discipline' => 'music', 'genre' => '재즈/소울']],
      ['label' => '클래식', 'query' => ['discipline' => 'music', 'genre' => '클래식']],
      ['label' => '국악', 'query' => ['discipline' => 'music', 'genre' => '국악']],
      ['label' => 'OST', 'query' => ['discipline' => 'music', 'genre' => 'OST']],
      ['label' => 'DJ', 'query' => ['discipline' => 'music', 'genre' => 'DJ']],
      ['label' => '어쿠스틱', 'query' => ['discipline' => 'music', 'genre' => '어쿠스틱']],
      ['label' => 'EDM', 'query' => ['discipline' => 'music', 'genre' => 'EDM']],
      ['label' => '뮤지컬', 'query' => ['discipline' => 'music', 'genre' => '뮤지컬']],
      ['label' => '키즈', 'query' => ['discipline' => 'music', 'genre' => '키즈']],
    ],
    'mc' => [
      ['label' => '전문 MC', 'query' => ['discipline' => 'mc', 'mc_type' => '전문MC']],
      ['label' => '아나운서', 'query' => ['discipline' => 'mc', 'mc_type' => '아나운서']],
      ['label' => '홈쇼핑', 'query' => ['discipline' => 'mc', 'mc_type' => '홈쇼핑']],
      ['label' => '주례', 'query' => ['discipline' => 'mc', 'mc_type' => '주례']],
      ['label' => '돌잔치', 'query' => ['discipline' => 'mc', 'mc_type' => '돌잔치']],
      ['label' => '기업행사', 'query' => ['discipline' => 'mc', 'mc_type' => '기업행사']],
      ['label' => '학술/포럼', 'query' => ['discipline' => 'mc', 'mc_type' => '학술']],
      ['label' => '국제행사', 'query' => ['discipline' => 'mc', 'mc_type' => '국제행사']],
      ['label' => '토크쇼', 'query' => ['discipline' => 'mc', 'mc_style' => '밝은']],
      ['label' => '차분한 진행', 'query' => ['discipline' => 'mc', 'mc_style' => '차분한']],
      ['label' => '유쾌한 진행', 'query' => ['discipline' => 'mc', 'mc_style' => '활발한']],
    ],
    'dance' => [
      ['label' => 'K-POP', 'query' => ['discipline' => 'dance', 'dance_style' => 'K-POP']],
      ['label' => '스트릿', 'query' => ['discipline' => 'dance', 'dance_style' => '스트릿']],
      ['label' => '비보이', 'query' => ['discipline' => 'dance', 'dance_style' => '비보이']],
      ['label' => '락킹', 'query' => ['discipline' => 'dance', 'dance_style' => '락킹']],
      ['label' => '왁킹', 'query' => ['discipline' => 'dance', 'dance_style' => '왁킹']],
      ['label' => '팝핑', 'query' => ['discipline' => 'dance', 'dance_style' => '팝핑']],
      ['label' => '현대무용', 'query' => ['discipline' => 'dance', 'dance_style' => '현대무용']],
      ['label' => '발레', 'query' => ['discipline' => 'dance', 'dance_style' => '발레']],
      ['label' => '치어', 'query' => ['discipline' => 'dance', 'dance_style' => '퍼포먼스']],
      ['label' => '댄스스포츠', 'query' => ['discipline' => 'dance', 'dance_style' => '댄스스포츠']],
    ],
    'performance' => [
      ['label' => '마술', 'query' => ['discipline' => 'performance', 'q' => '마술']],
      ['label' => '서커스', 'query' => ['discipline' => 'performance', 'q' => '서커스']],
      ['label' => 'LED', 'query' => ['discipline' => 'performance', 'q' => 'LED']],
      ['label' => '저글링', 'query' => ['discipline' => 'performance', 'q' => '저글링']],
      ['label' => '샌드아트', 'query' => ['discipline' => 'performance', 'q' => '샌드아트']],
      ['label' => '드론쇼', 'query' => ['discipline' => 'performance', 'q' => '드론']],
      ['label' => '파이어쇼', 'query' => ['discipline' => 'performance', 'q' => '파이어']],
      ['label' => '벌룬', 'query' => ['discipline' => 'performance', 'q' => '벌룬']],
      ['label' => '마임', 'query' => ['discipline' => 'performance', 'q' => '마임']],
      ['label' => '버블쇼', 'query' => ['discipline' => 'performance', 'q' => '버블']],
      ['label' => '퍼레이드', 'query' => ['discipline' => 'performance', 'q' => '퍼레이드']],
    ],
    'plan' => [
      ['label' => '기획공연', 'query' => ['discipline' => 'plan', 'q' => '기획공연']],
      ['label' => '테마공연', 'query' => ['discipline' => 'plan', 'q' => '테마']],
      ['label' => '쇼케이스', 'query' => ['discipline' => 'plan', 'q' => '쇼케이스']],
      ['label' => '콜라보', 'query' => ['discipline' => 'plan', 'q' => '콜라보']],
      ['label' => '패키지', 'query' => ['discipline' => 'plan', 'q' => '패키지']],
      ['label' => '레퍼토리', 'query' => ['discipline' => 'plan', 'q' => '레퍼토리']],
      ['label' => '오프닝', 'query' => ['discipline' => 'plan', 'q' => '오프닝']],
      ['label' => '피날레', 'query' => ['discipline' => 'plan', 'q' => '피날레']],
    ],
    'celebrity' => [
      ['label' => '셀럽', 'query' => ['discipline' => 'celebrity', 'q' => '셀럽']],
      ['label' => '인플루언서', 'query' => ['discipline' => 'celebrity', 'q' => '인플루언서']],
      ['label' => '배우/방송인', 'query' => ['discipline' => 'celebrity', 'q' => '배우']],
      ['label' => '유튜버', 'query' => ['discipline' => 'celebrity', 'q' => '유튜버']],
      ['label' => '틱톡커', 'query' => ['discipline' => 'celebrity', 'q' => '틱톡']],
      ['label' => '스포츠 스타', 'query' => ['discipline' => 'celebrity', 'q' => '스포츠']],
      ['label' => '셰프/쿠킹', 'query' => ['discipline' => 'celebrity', 'q' => '셰프']],
      ['label' => '스페셜 게스트', 'query' => ['discipline' => 'celebrity', 'q' => '게스트']],
    ],
  ];
  $activeCategory = $activeCategory ?? (request()->route('discipline') ?? request()->query('discipline') ?? 'all');
  $activeCategory = $activeCategory === '' ? 'all' : $activeCategory;
  $cartCount = null;
  if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('intake_requests')) {
    try {
      $cartCount = \App\Models\IntakeRequest::query()
        ->where('contact_email', auth()->user()->email)
        ->where('category', 'option')
        ->where('notes', 'like', '%\"type\":\"option_order\"%')
        ->where(function($q){ $q->whereNull('status')->orWhere('status', '!=', 'closed'); })
        ->count();
    } catch (\Throwable $e) {
      $cartCount = null;
    }
  }
@endphp

<header class="enc-top" aria-label="상단 내비게이션">
  <div class="enc-container enc-nav">
    <a class="enc-brand" href="{{ url('/') }}" aria-label="Encore 홈">
      <img class="brand-logo" src="{{ asset('image/encore-logo.svg') }}" alt="Encore">
    </a>
    <div class="enc-nav-right">
      <span class="enc-badge" aria-label="베타">BETA</span>
      @guest
        <a class="enc-link" href="{{ route('register') }}">회원가입</a>
        <a class="enc-link" href="{{ route('login') }}">로그인</a>
      @endguest
      @auth
        <a class="enc-mypage" href="{{ route('member.dashboard') }}">Mypage</a>
        <form method="post" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button class="enc-link" style="background:none;border:none;cursor:pointer" type="submit">로그아웃</button>
        </form>
      @endauth
      @auth
        <a class="enc-cart" href="{{ route('member.inquiries', ['type' => 'instant']) }}" aria-label="장바구니">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="20" r="1.5"></circle>
            <circle cx="17" cy="20" r="1.5"></circle>
            <path d="M3 4h2l2.4 12.4a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 8H7"></path>
          </svg>
          @if(!is_null($cartCount) && $cartCount > 0)
            <span class="count">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
          @endif
        </a>
      @endauth
      @guest
        <a class="enc-cart" href="{{ route('login') }}" aria-label="장바구니">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="20" r="1.5"></circle>
            <circle cx="17" cy="20" r="1.5"></circle>
            <path d="M3 4h2l2.4 12.4a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 8H7"></path>
          </svg>
        </a>
      @endguest
      <button id="themeToggle" class="theme-toggle" type="button" aria-label="테마 전환"><span class="tlabel">Dark</span></button>
    </div>
  </div>
  @if(empty($hideCategoryNav))
    <div class="enc-categorybar" aria-label="전체 카테고리">
      <div class="enc-container">
        <div class="enc-categorylist" id="encCategoryList" role="tablist" data-active="{{ $activeCategory }}">
          @foreach($categoryLinks as $cat)
            @php
              $slug = $cat['slug'];
              $isActive = $activeCategory === $slug;
              $items = $categorySubLinks[$slug] ?? [];
              $hasPanel = !empty($items);
              $browseUrl = $slug === 'all' ? route('artists.browse') : route('artists.browse', ['discipline' => $slug]);
            @endphp
            @if($hasPanel)
              <button class="enc-categorylink{{ $isActive ? ' active' : '' }}" type="button" data-category="{{ $slug }}" aria-expanded="false">
                {{ $cat['label'] }}
                <span class="chev">▾</span>
              </button>
            @else
              <a class="enc-categorylink{{ $isActive ? ' active' : '' }}" href="{{ $browseUrl }}">{{ $cat['label'] }}</a>
            @endif
          @endforeach
        </div>
        <div class="enc-categorypanel" id="encCategoryPanel" aria-hidden="true">
          <div class="panel-grid">
            @foreach($categoryLinks as $cat)
              @php
                $slug = $cat['slug'];
                if ($slug === 'all') continue;
                $items = $categorySubLinks[$slug] ?? [];
                $browseUrl = route('artists.browse', ['discipline' => $slug]);
              @endphp
              <div class="panel-card" data-category="{{ $slug }}">
                <div class="panel-title">
                  <strong>{{ $cat['label'] }}</strong>
                  <a href="{{ $browseUrl }}">전체 보기</a>
                </div>
                <div class="panel-items">
                  @foreach($items as $item)
                    <a class="panel-item" href="{{ route('artists.browse', $item['query']) }}">{{ $item['label'] }}</a>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endif
</header>

<script id="enc-category-script">
  (function(){
    const list = document.getElementById('encCategoryList');
    const panel = document.getElementById('encCategoryPanel');
    if (!list || !panel) return;
    const buttons = Array.from(list.querySelectorAll('.enc-categorylink'));
    const cards = Array.from(panel.querySelectorAll('.panel-card'));
    const openPanel = (slug) => {
      panel.classList.add('open');
      panel.setAttribute('aria-hidden', 'false');
      buttons.forEach(btn => {
        const active = btn.dataset.category === slug;
        btn.classList.toggle('active', active);
        btn.setAttribute('aria-expanded', active ? 'true' : 'false');
      });
      cards.forEach(card => {
        card.classList.toggle('active', card.dataset.category === slug);
      });
    };
    const closePanel = () => {
      panel.classList.remove('open');
      panel.setAttribute('aria-hidden', 'true');
      buttons.forEach(btn => btn.setAttribute('aria-expanded', 'false'));
    };
    list.addEventListener('click', (e) => {
      const btn = e.target.closest('.enc-categorylink');
      if (!btn) return;
      const slug = btn.dataset.category;
      if (!slug) return;
      if (btn.getAttribute('aria-expanded') === 'true') {
        closePanel();
      } else {
        openPanel(slug);
      }
    });
    document.addEventListener('click', (e) => {
      if (panel.contains(e.target) || list.contains(e.target)) return;
      closePanel();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closePanel();
    });
  })();
</script>

<script id="enc-theme-toggle-script">
  (function(){
    if (window.__encThemeInit) return; // avoid multiple inits
    window.__encThemeInit = true;
    const THEME_KEY = 'enc_theme';
    const root = document.documentElement;
    const btn = document.getElementById('themeToggle');
    function setTheme(t){
      root.setAttribute('data-theme', t);
      try { localStorage.setItem(THEME_KEY, t); } catch(e) {}
      const label = btn?.querySelector('.tlabel');
      if (label) label.textContent = t === 'light' ? 'Light' : 'Dark';
    }
    let init = 'dark';
    try { init = (localStorage.getItem(THEME_KEY) === 'light') ? 'light' : 'dark'; } catch(e) {}
    setTheme(init);
    btn?.addEventListener('click', function(){ setTheme(root.getAttribute('data-theme') === 'light' ? 'dark' : 'light'); });
  })();
  </script>

@php
  $showMemberNav = empty($hideMemberNav)
    && (request()->routeIs('member.*') || request()->routeIs('profile.edit'));
@endphp
@auth
  @if($showMemberNav)
    @include('member.partials.nav')
  @endif
@endauth

@if(!empty($subTitle))
  <style>
    .enc-subbar { border-bottom:1px solid var(--border); background: transparent; }
    .enc-subbar .wrap { max-width:1120px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; }
    .enc-subbar .title { margin:0; font-weight:800; }
  </style>
  <div class="enc-subbar" aria-label="페이지 제목">
    <div class="wrap">
      <h1 class="title">{{ $subTitle }}</h1>
    </div>
  </div>
@endif
