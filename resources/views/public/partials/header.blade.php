{{-- Shared public header (matches home) --}}
<style id="enc-theme-vars">
  /* Global theme variables for all public pages */
  :root {
    --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
      radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
      #0b090a;
    --fg: #f7f1e9;
    --muted: #b6a89a;
    --accent: #f3c652;
    --accent-hover: #f0b840;
    --accent-2: #8d1f2d;
    --accent-text: #1b130f;
    --ring: #8d1f2d;
    --btn: #8d1f2d;
    --btn-hover: #a12639;
    --btn-text: #f7f1e9;
    --card: #151012;
    --card-alt: #1b1316;
    --border: #2a1c22;
    --chip: rgba(141, 31, 45, 0.2);
    --chip-border: rgba(141, 31, 45, 0.4);
    --font-sans: "Noto Sans KR", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
    --font-display: "Noto Serif KR", "Apple SD Gothic Neo", "Malgun Gothic", serif;
  }
  [data-theme="light"] {
    --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%),
      linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%);
    --fg: #2b1b1b;
    --muted: #6b5b53;
    --accent: #e3b648;
    --accent-hover: #d5a63b;
    --accent-2: #7a1e2e;
    --accent-text: #1b130f;
    --ring: #7a1e2e;
    --btn: #7a1e2e;
    --btn-hover: #8b2638;
    --btn-text: #fff7e6;
    --card: #fffdf8;
    --card-alt: #f6ecdd;
    --border: #e6d4c0;
    --chip: rgba(122, 30, 46, 0.12);
    --chip-border: rgba(122, 30, 46, 0.28);
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
  .enc-link { display:inline-flex; align-items:center; justify-content:center; height:36px; padding:0 12px; border-radius:999px; border:1px solid transparent; color: var(--muted); text-decoration:none; font-weight:600; }
  .enc-link:hover { background: rgba(141,31,45,.12); color: var(--fg); border-color: rgba(141,31,45,.35); }
  .enc-badge { display:inline-flex; align-items:center; font-size:12px; color:#f5e2b2; background: rgba(141,31,45,.18); border:1px solid rgba(141,31,45,.4); padding:2px 8px; border-radius:999px; }
  [data-theme="light"] .enc-badge { background: var(--accent-2); border-color: var(--accent-2); color:#fff; }
  .theme-toggle { display:inline-flex; align-items:center; justify-content:center; gap:8px; height:36px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); cursor:pointer; }
  [data-theme="light"] .theme-toggle { background:#ffffff; color:#1f2937; border-color:#d7dce2; }
  /* Mypage pill button (brand tone) */
  .enc-mypage{
    display:inline-flex; align-items:center; gap:6px;
    height: 36px; padding:0 12px; border-radius:999px;
    background: var(--btn); border:1px solid var(--btn); color:var(--btn-text); font-weight:700; text-decoration:none;
    box-shadow: 0 4px 12px rgba(141,31,45,.22);
    transition: transform .08s ease, background .2s ease, box-shadow .2s ease;
  }
  .enc-mypage:hover{ background: var(--btn-hover); border-color: var(--btn-hover); color:var(--btn-text); text-decoration:none; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(141,31,45,.3); }
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
      <button id="themeToggle" class="theme-toggle" type="button" aria-label="테마 전환"><span class="tlabel">Dark</span></button>
    </div>
  </div>
</header>

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

@auth
  @if(empty($hideMemberNav))
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
