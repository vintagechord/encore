<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>Encore | 아티스트 추천 서비스</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO / Meta -->
    <meta name="description" content="프로모션/행사 목적에 맞는 아티스트를 빠르게 추천받아보세요. 간단한 문의만 작성하면 최신 데이터로 선별된 결과를 전달해드립니다.">
    <link rel="canonical" href="{{ url('/') }}">
    <meta name="robots" content="index,follow">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Encore">
    <meta property="og:title" content="Encore | 아티스트 추천 서비스">
    <meta property="og:description" content="프로모션/행사 목적에 맞는 아티스트를 빠르게 추천받아보세요. 간단한 문의만 작성하면 최신 데이터로 선별된 결과를 전달해드립니다.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('og/encore-og.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Encore | 아티스트 추천 서비스">
    <meta name="twitter:description" content="프로모션/행사 목적에 맞는 아티스트를 빠르게 추천받아보세요. 간단한 문의만 작성하면 최신 데이터로 선별된 결과를 전달해드립니다.">
    <meta name="twitter:image" content="{{ asset('og/encore-og.png') }}">

    <!-- Icons / Theme -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#050912">
    <meta name="color-scheme" content="dark light">

    <style>
        :root {
            --bg: #050912;
            --fg: #e6edff;
            --muted: #97a6c9;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --ring: #4f46e5;
            --card: #0f1729;
            --card-alt: #152033;
            --border: #1f2b41;
            --chip: rgba(99, 102, 241, 0.18);
            --chip-border: rgba(99, 102, 241, 0.35);
        }

        /* Light theme overrides */
        [data-theme="light"] {
            --bg: #f8fafc;
            --fg: #0f1729;
            --muted: #475569;
            --accent: #4f46e5;
            --accent-hover: #4338ca;
            --ring: #4f46e5;
            --card: #ffffff;
            --card-alt: #f1f5f9;
            --border: #d7dce2;
            --chip: rgba(79, 70, 229, 0.10);
            --chip-border: rgba(79, 70, 229, 0.28);
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
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, Apple SD Gothic Neo, Malgun Gothic, sans-serif;
            color: var(--fg);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        main {
            flex: 1
        }

        a {
            color: inherit;
            text-decoration: none
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid var(--ring);
            outline-offset: 2px;
            border-radius: 8px;
        }

        /* Layout */
        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 20px
        }
        @media (max-width: 768px) {
            .container { padding-left: 18px; padding-right: 18px; }
        }

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(7, 12, 24, .88);
            backdrop-filter: saturate(200%) blur(12px);
            border-bottom: 1px solid var(--border);
        }

        [data-theme="light"] header { background: rgba(255,255,255,.85); backdrop-filter:saturate(180%) blur(10px); }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 0;
            flex-wrap: wrap
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700
        }

        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            color: #7dd3fc;
            background: rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(14, 165, 233, 0.32);
            padding: 2px 8px;
            border-radius: 999px
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap
        }

        .theme-toggle { display:inline-flex; align-items:center; gap:8px; padding:8px 10px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); cursor:pointer; }
        .banner-area { border-bottom:1px solid var(--border); background: var(--card); }
        .banner-grid { display:grid; grid-template-columns:1fr; gap:10px; }
        .banner { display:block; overflow:hidden; border-radius:12px; border:1px solid var(--border); }
        .banner img { display:block; width:100%; height:auto; }

        /* Direct request box */
        .request-card { background: var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; box-shadow: inset 0 1px 0 rgba(255,255,255,0.02); }
        .request-form { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
        .request-input { height:44px; padding:0 14px; border-radius:12px; border:1px solid var(--border); background: var(--card-alt); color: var(--fg); min-width:280px; }
        .request-input::placeholder { color: var(--muted); opacity:.9; }
        .request-btn { height:44px; padding:0 16px; border-radius:12px; }

        /* Light specific readability tweaks */
        [data-theme="light"] .hero { background: linear-gradient(180deg,#ffffff 0%, #f1f5fb 100%); border-bottom:1px solid var(--border); }
        [data-theme="light"] .card { box-shadow: 0 12px 28px rgba(2, 6, 23, 0.06); }
        [data-theme="light"] .btn-ghost { border-color: #c6ced8; }

        .nav-link {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid transparent;
            color: var(--muted);
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.12);
            color: var(--fg);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .nav-link[aria-current="page"] {
            border-color: rgba(99, 102, 241, 0.35);
            background: rgba(99, 102, 241, 0.18);
            color: var(--fg);
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(1100px 420px at 10% -20%, rgba(59, 130, 246, 0.28) 0%, rgba(5, 9, 18, 0) 60%),
                radial-gradient(900px 360px at 95% 10%, rgba(236, 72, 153, 0.25) 0%, rgba(5, 9, 18, 0) 58%),
                linear-gradient(180deg, #050912 0%, #070c1f 100%);
            border-bottom: 1px solid var(--border);
        }

        .hero-inner {
            padding: 72px 0 56px
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: clamp(28px, 5vw, 44px);
            line-height: 1.12;
            letter-spacing: -0.02em
        }

        .hero p {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: clamp(16px, 2.8vw, 18px)
        }

        .cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-weight: 600;
            cursor: pointer;
            transition: transform .04s ease, background .2s ease, border-color .2s ease;
            user-select: none;
        }

        .btn:active {
            transform: translateY(1px)
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent)
        }

        .btn-primary:hover {
            background: var(--accent-hover)
        }

        .btn-ghost {
            background: transparent;
            border-color: var(--border);
            color: var(--fg);
        }

        .btn-ghost:hover {
            background: rgba(99, 102, 241, 0.16);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .features {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 28px 0
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .card h3 {
            margin: 0 0 6px;
            font-size: 16px
        }

        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5
        }

        .ico {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--chip);
            border: 1px solid var(--chip-border)
        }

        /* ▼ Testimonials */
        .testimonials {
            padding: 8px 0 28px
        }

        .testi-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        .success-stories {
            padding: 48px 0 24px;
            border-top: 1px solid var(--border);
        }

        .success-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .success-title {
            margin: 0;
            font-size: clamp(24px, 4vw, 32px);
        }

        .success-eyebrow {
            display: inline-flex;
            font-size: 13px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .success-tabs {
            display: inline-flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .success-tab {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }

        .success-tab.active {
            background: rgba(99, 102, 241, 0.2);
            color: var(--fg);
            border-color: rgba(99, 102, 241, 0.4);
        }

        .stories-frame {
            position: relative;
            overflow: hidden;
            cursor: grab;
            touch-action: pan-y;
            user-select: none;
        }

        .stories-track {
            display: none;
            overflow: hidden; /* mask overflowing cards */
        }

        .stories-track.active {
            display: block;
        }

        /* Default: show current page as grid (fallback when JS not transforming to flow) */
        .stories-slide {
            display: none;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }
        .stories-slide.current { display: grid; }

        /* Flow row: horizontally scrolling cards */
        .stories-row {
            display: flex;
            gap: 14px;
            align-items: stretch;
            will-change: transform;
        }

        /* Make card width consistent for smooth flow */
        .stories-row .story-card { width: clamp(220px, 28vw, 280px); flex: 0 0 auto; }

        .story-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .story-thumb {
            position: relative;
            padding-top: 62%;
            background: rgba(148, 163, 208, 0.12);
        }

        .story-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .story-body {
            padding: 14px 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .story-body h3 {
            margin: 0;
            font-size: 18px;
        }

        .story-meta {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.5;
        }

        .story-meta strong {
            display: inline-block;
            min-width: 44px;
            color: var(--fg);
        }

        .stories-controls { display: none !important; }
        .stories-nav, .stories-btn { display: none !important; }

        .t-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .t-kind {
            font-size: 12px;
            color: #c7d2ff;
            background: rgba(99, 102, 241, 0.16);
            border: 1px solid rgba(99, 102, 241, 0.35);
            border-radius: 999px;
            padding: 2px 8px;
            display: inline-flex;
            width: max-content
        }
        /* Light theme: make badges fully legible (brand filled) */
        [data-theme="light"] .t-kind {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .t-title {
            margin: 0;
            font-size: 16px
        }

        blockquote {
            margin: 0;
            color: var(--fg);
            font-size: 14px;
            line-height: 1.6
        }

        .t-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 12px
        }

        .stars {
            letter-spacing: 1px;
            color: #facc15;
        }

        .sr-only {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            overflow: hidden
        }

        @media (min-width:720px) {
            .features {
                grid-template-columns: repeat(3, 1fr);
                gap: 16px
            }

            .hero-inner {
                padding: 96px 0 72px
            }

            .testi-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 16px
            }

            .stories-slide {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .footer {
            border-top: 1px solid var(--border);
            margin-top: 36px;
            background: rgba(5, 9, 18, 0.7);
        }

        .footer-inner {
            padding: 18px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            color: var(--muted);
            font-size: 13px
        }

        /* AB 토글 */
        .ab-toggle {
            position: fixed;
            right: 14px;
            bottom: 14px;
            z-index: 40;
            background: rgba(7, 12, 24, 0.95);
            color: var(--fg);
            border: 1px solid var(--border);
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            box-shadow: 0 12px 30px rgba(2, 4, 12, .55);
        }

        .ab-toggle .label {
            font-size: 12px;
            opacity: .85;
            margin-right: 2px
        }

        .ab-btn {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }

        .ab-btn:hover {
            background: rgba(99, 102, 241, 0.16);
            color: var(--fg);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .ab-btn.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* Skip link for a11y */
        .skip {
            position: absolute;
            left: -9999px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden
        }

        .skip:focus {
            position: static;
            width: auto;
            height: auto;
            padding: 8px 12px;
            margin: 8px;
            background: var(--accent);
            color: #fff;
            border-radius: 8px
        }
    </style>
</head>

<body>
@php
    use Illuminate\Support\Str;
    $storyGroups = isset($storyGroups) ? $storyGroups : collect();
    $firstCategory = $storyGroups->keys()->first();
    $initialPages = $storyGroups->isNotEmpty() ? max(1, $storyGroups->first()->chunk(4)->count()) : 1;
@endphp
    <a class="skip" href="#main">본문 바로가기</a>

    <header aria-label="상단 내비게이션">
            <div class="container nav">
            <a class="brand" href="{{ url('/') }}" aria-label="Encore 홈" aria-current="page">
                <span aria-hidden="true" style="display:inline-flex;width:22px;height:22px;border-radius:6px;background:linear-gradient(135deg,#6366f1,#ec4899);"></span>
                <span>Encore</span>
            </a>
            <div class="nav-right">
                <span class="badge" aria-label="베타 배지">BETA</span>
                @guest
                  <a class="nav-link" href="{{ route('register') }}">회원가입</a>
                  <a class="nav-link" href="{{ route('login') }}">로그인</a>
                @endguest
                @auth
                  <style>.enc-mypage{display:inline-flex;align-items:center;gap:6px;padding:8px 12px;border-radius:999px;background:var(--accent);border:1px solid var(--accent);color:#fff;font-weight:700;text-decoration:none;box-shadow:0 6px 16px rgba(99,102,241,.25);transition:transform .08s ease,background .2s ease,box-shadow .2s ease}.enc-mypage:hover{background:var(--accent-hover);border-color:var(--accent-hover);color:#fff;text-decoration:none;transform:translateY(-1px);box-shadow:0 10px 22px rgba(99,102,241,.28)}</style>
                  <a class="enc-mypage" href="{{ route('member.dashboard') }}">Mypage</a>
                  <form method="post" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button class="nav-link" style="background:none;border:none;padding:8px 10px;cursor:pointer" type="submit">로그아웃</button>
                  </form>
                @endauth
                <button id="themeToggle" class="theme-toggle" type="button" aria-label="테마 전환"><span class="tlabel">Dark</span></button>
            </div>
        </div>
    </header>

    <main id="main" aria-live="polite">
        @if(isset($banners) && $banners->isNotEmpty())
        <section class="banner-area" aria-label="메인 배너" id="encMainBanner">
          <style>
            .bn-wrap{ max-width: 920px; margin: 0 auto; position:relative; }
            .bn-viewport{ overflow:hidden; border-radius:14px; border:1px solid var(--border); background: var(--card); position:relative; }
            .bn-track{ display:flex; transition: transform .35s ease; }
            .bn-item{ flex: 0 0 100%; display:block; text-align:center; }
            .bn-item img{ display:block; max-width:100%; height:auto; }
            .bn-nav{ position:absolute; inset:0; display:flex; align-items:center; justify-content:space-between; pointer-events:none; }
            .bn-btn{ pointer-events:auto; width:40px; height:40px; border-radius:999px; border:1px solid var(--border); background: rgba(15,23,41,.8); color: var(--fg); display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
            .bn-btn:hover{ background: rgba(99,102,241,.2); border-color: var(--chip-border); }
            /* Floating close bar at bottom center */
            .bn-closebar{ position:absolute; left:50%; transform:translateX(-50%); bottom:8px; display:flex; justify-content:center; width:100%; pointer-events:none; z-index:2; }
            .bn-closebar .bn-toggle{ pointer-events:auto; }
            .bn-toggle{ display:inline-flex; align-items:center; gap:6px; background: rgba(99,102,241,.12); border:1px solid var(--chip-border); color: var(--fg); border-radius:999px; padding:6px 12px; cursor:pointer; box-shadow: inset 0 1px 0 rgba(255,255,255,.04); }
            .bn-toggle:hover{ background: rgba(99,102,241,.2); }
            /* Light theme: solid accent for clear contrast */
            [data-theme="light"] .bn-toggle{ background: var(--accent); border-color: var(--accent); color:#fff; }
            [data-theme="light"] .bn-toggle:hover{ background: var(--accent-hover); border-color: var(--accent-hover); color:#fff; }
            .bn-toggle .chev{ transition: transform .2s ease; }
            .bn-reopen{ position: sticky; top: 0; display:none; justify-content:center; padding:8px 0; }
            [data-collapsed="true"] .bn-wrap{ display:none; }
            [data-collapsed="true"] .bn-reopen{ display:flex; }
            [data-collapsed="true"] .bn-toggle .chev{ transform: rotate(180deg); }
            /* Section spacing: add symmetric breathing room */
            .banner-area{ padding: 8px 0 16px; }
          </style>
          <div class="bn-wrap">
            <div class="bn-viewport" id="bnViewport">
              <div class="bn-track" id="bnTrack">
                @foreach($banners as $b)
                  @php $img = $b->image_path ? asset('storage/'.$b->image_path) : null; @endphp
                  @if($img)
                    <a class="bn-item" href="{{ $b->link_url ?: '#' }}" @if($b->link_url) target="_blank" rel="noopener" @endif>
                      <img src="{{ $img }}" alt="{{ $b->title }}">
                    </a>
                  @endif
                @endforeach
              </div>
              @if($banners->count() > 1)
              <div class="bn-nav" aria-hidden="true">
                <button class="bn-btn" type="button" id="bnPrev" aria-label="이전">‹</button>
                <button class="bn-btn" type="button" id="bnNext" aria-label="다음">›</button>
              </div>
              @endif
              <div class="bn-closebar" aria-hidden="false">
                <button class="bn-toggle" type="button" id="bnCloseBtn" aria-label="배너 닫기"><span class="chev">▴</span> 배너 닫기</button>
              </div>
            </div>
          </div>
          <div class="bn-reopen" id="bnReopen"><button class="bn-toggle" type="button"><span class="chev">▾</span> 배너 열기</button></div>
          <script>
            (function(){
              const key='enc_banner_closed';
              const sec=document.getElementById('encMainBanner');
              const wrap=sec?.querySelector('.bn-wrap');
              const reopen=document.getElementById('bnReopen');
              const isClosed=()=>{ try{return localStorage.getItem(key)==='1';}catch(e){return false;} };
              const setClosed=(v)=>{ try{localStorage.setItem(key, v?'1':'0');}catch(e){} };
              function applyVis(){ if(!sec) return; sec.setAttribute('data-collapsed', isClosed() ? 'true' : 'false'); }
              applyVis();
              document.getElementById('bnCloseBtn')?.addEventListener('click', (ev)=>{ ev.preventDefault(); setClosed(true); applyVis(); });
              reopen?.querySelector('button')?.addEventListener('click', (ev)=>{ ev.preventDefault(); setClosed(false); applyVis(); });

              // slider
              const track=document.getElementById('bnTrack');
              const vp=document.getElementById('bnViewport');
              const items=track?Array.from(track.children):[];
              let idx=0; function clamp(i){ return (i+items.length)%items.length; }
              function go(i){ idx=clamp(i); const w = vp?.clientWidth || 0; const x = -idx * w; track.style.transform = 'translateX('+x+'px)'; }
              window.addEventListener('resize', ()=>go(idx));
              document.getElementById('bnPrev')?.addEventListener('click', ()=>go(idx-1));
              document.getElementById('bnNext')?.addEventListener('click', ()=>go(idx+1));
              // init
              if(track){ go(0); }
            })();
          </script>
        </section>
        @endif
        <section class="hero" aria-labelledby="home-title">
            <div class="container hero-inner">
                <h1 id="home-title"><span id="copy-title">행사에 딱 맞는 아티스트,<br>바로 추천받으세요.</span></h1>
                <p id="copy-desc">
                    간단한 요구사항만 알려주시면 예산·콘셉트·타깃에 맞춘 후보를 선별해
                    공유 링크로 전달합니다. 필요하면 언제든 새 링크로 회수·재발급도 가능해요.
                </p>
                <style>
                  .optgrid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:12px; margin-top:10px; }
                  .optcard{ position:relative; display:flex; flex-direction:column; min-height:200px; border:1px solid var(--border); border-radius:14px; background:var(--card); padding:20px; cursor:pointer; transition: border-color .2s ease, box-shadow .2s ease, transform .08s ease; text-decoration:none; color:inherit; }
                  .optcard:hover{ border-color: var(--chip-border); box-shadow: inset 0 0 0 2px rgba(99,102,241,.15); }
                  .optcard.active{ border-color: var(--accent); box-shadow: inset 0 0 0 2px rgba(99,102,241,.35); }
                  .optcard h3{ margin:0 0 8px; font-size: clamp(18px, 2.2vw, 22px); font-weight: 800; letter-spacing: -0.01em; }
                  .optcard p{ margin:0 0 10px; color:var(--muted); font-size:14px; min-height:40px; }
                  .optcard .btn{ margin-top:auto; border:1px solid var(--chip-border); background: var(--card-alt); color: var(--accent); }
                  .optcard:not(.active) .btn{ background: var(--card-alt); color: var(--accent); border-color: var(--chip-border); }
                  .optcard.active .btn{ background: var(--accent); color:#fff; border-color: var(--accent); }
                </style>
                <div class="optgrid" id="homeOptGrid" role="tablist" aria-label="문의 옵션">
                  <div class="optcard active" data-mode="instant" role="tab" aria-selected="true">
                    <h3>1초 Set</h3>
                    <p>옵션 입력 즉시 3가지 추천안 자동 생성. 마음에 들지 않으면 재생성 가능.</p>
                    <a class="btn" href="{{ route('inquiry.create', ['mode'=>'instant']) }}">추천셋 즉시 생성</a>
                  </div>
                  <div class="optcard" data-mode="one_day" role="tab" aria-selected="false">
                    <h3>1일 Set</h3>
                    <p>요구사항을 작성해 보내주시면 관리자가 큐레이션한 3가지 셋을 1일 내 전달.</p>
                    <a class="btn" href="{{ route('inquiry.create', ['mode'=>'one_day']) }}">관리자의 추천셋</a>
                  </div>
                  <div class="optcard" data-mode="direct" role="tab" aria-selected="false">
                    <h3>아티스트 맞춤형</h3>
                    <p>원하는 아티스트를 지정해 섭외 요청하기.</p>
                    <a class="btn" href="{{ route('inquiry.create', ['mode'=>'direct']) }}">아티스트 지정 섭외</a>
                  </div>
                </div>
                <script>
                  (function(){
                    const grid = document.getElementById('homeOptGrid');
                    if(!grid) return;
                    grid.addEventListener('click', function(e){
                      const card = e.target.closest('.optcard');
                      if(!card) return;
                      // 내부 버튼 클릭은 활성화만 유지하고 기본 이동 허용
                      if (e.target.closest('a.btn')) return;
                      // 카드 클릭 시 활성화 토글만 수행
                      grid.querySelectorAll('.optcard').forEach(c=>{ c.classList.remove('active'); c.setAttribute('aria-selected','false'); });
                      card.classList.add('active');
                      card.setAttribute('aria-selected','true');
                      e.preventDefault();
                    });
                  })();
                </script>

                {{-- (삭제) 기능 카드 3개 섹션 --}}

            </div>
        </section>

        @if($storyGroups->isNotEmpty())
        <section id="success-stories" class="success-stories" aria-labelledby="success-title">
            <div class="container">
                <div class="success-head">
                    <div>
                        <span class="success-eyebrow">SUCCESS STORIES</span>
                        <h2 id="success-title" class="success-title">최근 섭외 성공 사례</h2>
                    </div>
                </div>

                <div class="stories-frame" data-active="{{ Str::slug($firstCategory ?: 'story') }}">
                    @foreach($storyGroups as $group => $stories)
                        @php
                            $slug = Str::slug($group ?: 'story');
                            $chunks = $stories->chunk(4);
                        @endphp
                        <div class="stories-track{{ $loop->first ? ' active' : '' }}" data-category="{{ $slug }}" data-pages="{{ max(1, $chunks->count()) }}">
                            @foreach($chunks as $pageIndex => $chunk)
                                <div class="stories-slide{{ $pageIndex === 0 ? ' current' : '' }}" data-page="{{ $pageIndex }}">
                                    @foreach($chunk as $story)
                                        <article class="story-card">
                                            <figure class="story-thumb">
                                                @if($story->thumbnail_path)
                                                    <img src="{{ asset('storage/'.$story->thumbnail_path) }}" alt="{{ $story->title }} 썸네일">
                                                @else
                                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:13px;">이미지 준비중</div>
                                                @endif
                                            </figure>
                                            <div class="story-body">
                                                <div class="muted" style="font-size:12px; letter-spacing:0.08em; text-transform:uppercase;">{{ $story->category }}</div>
                                                <h3>{{ $story->title }}</h3>
                                                @if($story->summary)
                                                    <p style="margin:0; font-size:14px; color:var(--muted);">{{ $story->summary }}</p>
                                                @endif
                                                <div class="story-meta">
                                                    @if($story->role)
                                                        <div><strong>구분</strong> {{ $story->role }}</div>
                                                    @endif
                                                    @if($story->event_name)
                                                        <div><strong>행사</strong> {{ $story->event_name }}</div>
                                                    @endif
                                                    @if($story->event_date)
                                                        <div><strong>일자</strong> {{ $story->event_date?->format('Y-m-d') }}</div>
                                                    @endif
                                                    @if($story->location)
                                                        <div><strong>장소</strong> {{ $story->location }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="stories-controls">
                    <div>
                        <span class="muted">페이지</span>
                        <span class="stories-indicator"><strong>1</strong> / <span class="stories-total">{{ $initialPages }}</span></span>
                    </div>
                    <div class="stories-nav">
                        <button class="stories-btn" type="button" data-dir="prev" aria-label="이전 사례" disabled>&larr;</button>
                        <button class="stories-btn" type="button" data-dir="next" aria-label="다음 사례">&rarr;</button>
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if(false)
        <!-- (삭제됨) 희망 아티스트 직접 요청 섹션 -->
        @endif

        <!-- ▼ 신규: 샘플 문의/후기 섹션 -->
        <section class="testimonials" aria-labelledby="testi-title">
            <div class="container">
                <h2 id="testi-title" class="sr-only">고객 문의 샘플 및 후기</h2>
                <div class="testi-grid" role="list">
                    <!-- 1) 샘플 문의 -->
                    <article class="t-card" role="listitem" aria-labelledby="t1-title">
                        <span class="t-kind" aria-label="유형">샘플 문의</span>
                        <h3 id="t1-title" class="t-title">대학 축제 · 힙합/R&B · 1,500만 원</h3>
                        <blockquote cite="#" aria-label="문의 상세">
                            9월 말 저녁 타임, 40분 내외 공연. 남녀 혼성 혹은 콜라보 가능 아티스트 위주로 후보 부탁드립니다.
                        </blockquote>
                        <div class="t-meta">
                            <span>예상 응답: 익일 오전 후보 6팀</span>
                            <span class="stars" aria-label="만족도 예시 별점 5점 만점 4.8점">★★★★★<span class="sr-only">평균 4.8/5</span></span>
                        </div>
                    </article>

                    <!-- 2) 후기 -->
                    <article class="t-card" role="listitem" aria-labelledby="t2-title">
                        <span class="t-kind" aria-label="유형">후기</span>
                        <h3 id="t2-title" class="t-title">기업 세미나 애프터파티</h3>
                        <blockquote cite="#" aria-label="고객 후기">
                            내부 승인까지 시간이 촉박했는데, 링크로 후보 공유가 빨라서 결정이 쉬웠습니다. 예산 범위도 명확했어요.
                        </blockquote>
                        <div class="t-meta">
                            <span><cite>마케팅팀 B실장</cite></span>
                            <span class="stars" aria-label="별점 5점">★★★★★<span class="sr-only">5/5</span></span>
                        </div>
                    </article>

                    <!-- 3) 후기 -->
                    <article class="t-card" role="listitem" aria-labelledby="t3-title">
                        <span class="t-kind" aria-label="유형">후기</span>
                        <h3 id="t3-title" class="t-title">리테일 팝업 기념 공연</h3>
                        <blockquote cite="#" aria-label="고객 후기">
                            타깃 연령대에 맞춘 추천이 정확했습니다. 공유 링크 회수/재발급으로 보안 걱정도 줄었어요.
                        </blockquote>
                        <div class="t-meta">
                            <span><cite>브랜드 매니저 K</cite></span>
                            <span class="stars" aria-label="별점 5점 만점 4.9점">★★★★★<span class="sr-only">4.9/5</span></span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- 홈 본문 FAQ 프리뷰 제거 (FAQ는 /faq 별도 페이지로 이동) --}}
    </main>

    @include('public.partials.footer')

    <!-- AB 토글 -->
    <div class="ab-toggle" role="group" aria-label="카피 A/B 테스트 토글" title="로컬에서만 적용됩니다">
        <span class="label">Copy</span>
        <button class="ab-btn" data-variant="A" type="button">A</button>
        <button class="ab-btn" data-variant="B" type="button">B</button>
        <button class="ab-btn" data-variant="C" type="button">C</button>
    </div>

    <script>
        (function() {
            const STORAGE_KEY = 'homeCopyVariant';
            const THEME_KEY = 'enc_theme';
            const $title = document.getElementById('copy-title');
            const $desc = document.getElementById('copy-desc');
            const $cta = document.getElementById('copy-cta')?.querySelector('.cta-text');

            const variants = {
                A: {
                    title: '행사에 딱 맞는 아티스트,<br>바로 추천받으세요.',
                    desc: '간단한 요구사항만 알려주시면 예산·콘셉트·타깃에 맞춘 후보를 선별해 공유 링크로 전달합니다. 필요하면 언제든 새 링크로 회수·재발급도 가능해요.',
                    cta: '문의하기'
                },
                B: {
                    title: '기획서에 꽂히는 섭외 후보,<br>내일 아침까지.',
                    desc: '핵심 조건만 남기면 밤사이 후보를 추려 정리합니다. 예산 가이드를 함께 드려 의사결정을 빠르게.',
                    cta: '바로 문의'
                },
                C: {
                    title: '예산·콘셉트·타깃 맞춤 추천,<br>링크로 깔끔하게 공유.',
                    desc: '공개 링크 발급/회수/재발급까지 한 번에. 내부 검토·외부 공유가 쉬워집니다.',
                    cta: '추천 받아보기'
                }
            };

            function applyVariant(key) {
                const v = variants[key] || variants.A;
                if ($title) $title.innerHTML = v.title;
                if ($desc) $desc.textContent = v.desc;
                if ($cta) $cta.textContent = v.cta;
                document.querySelectorAll('.ab-btn').forEach(b => {
                    b.classList.toggle('active', b.dataset.variant === key);
                });
                try {
                    localStorage.setItem(STORAGE_KEY, key);
                } catch (e) {}
            }

            const url = new URL(window.location.href);
            const fromQuery = (url.searchParams.get('ab') || '').toUpperCase();
            const saved = (() => {
                try {
                    return localStorage.getItem(STORAGE_KEY) || '';
                } catch (e) {
                    return '';
                }
            })();
            const initial = ['A', 'B', 'C'].includes(fromQuery) ? fromQuery : (['A', 'B', 'C'].includes(saved) ? saved : 'A');
            applyVariant(initial);

            document.querySelectorAll('.ab-btn').forEach(btn => {
                btn.addEventListener('click', () => applyVariant(btn.dataset.variant));
            });

            // Theme init + toggle
            const root = document.documentElement;
            const btnTheme = document.getElementById('themeToggle');
            const setTheme = (t) => {
                root.setAttribute('data-theme', t);
                try { localStorage.setItem(THEME_KEY, t); } catch (e) {}
                const label = btnTheme?.querySelector('.tlabel');
                if (label) label.textContent = t === 'light' ? 'Light' : 'Dark';
            };
            const savedTheme = (() => { try { return localStorage.getItem(THEME_KEY) || ''; } catch(e) { return ''; } })();
            setTheme(savedTheme === 'light' ? 'light' : 'dark');
            btnTheme?.addEventListener('click', () => setTheme(root.getAttribute('data-theme') === 'light' ? 'dark' : 'light'));

            const frame = document.querySelector('.stories-frame');
            if (frame) {
                const tabs = document.querySelectorAll('.success-tab');
                let activeCategory = frame.dataset.active || '';
                const GAP = 14;
                const SPEED = 0.35; // px per frame

                const getTrack = (slug) => frame.querySelector(`.stories-track[data-category="${slug}"]`);
                const allTracks = () => Array.from(frame.querySelectorAll('.stories-track'));

                let raf = 0;
                let offset = 0;
                let baseWidth = 0;
                let dragging = false;
                let startX = 0;
                let startOffset = 0;
                let row = null;

                function sumWidth(els, count) {
                    const arr = Array.from(els).slice(0, count);
                    let w = 0;
                    arr.forEach((el, idx) => {
                        w += el.getBoundingClientRect().width;
                        if (idx < arr.length - 1) w += GAP;
                    });
                    return Math.max(1, Math.round(w));
                }

                function buildRow(track) {
                    let existing = track.querySelector('.stories-row');
                    if (existing) return existing;
                    const originals = Array.from(track.querySelectorAll('.story-card'));
                    const origCount = originals.length;
                    if (!origCount) return null;
                    track.dataset.origCount = String(origCount);

                    const r = document.createElement('div');
                    r.className = 'stories-row';
                    originals.forEach(card => r.appendChild(card));
                    track.querySelectorAll('.stories-slide').forEach(s => s.remove());
                    track.appendChild(r);

                    baseWidth = sumWidth(r.children, origCount);
                    const minTotal = frame.clientWidth * 2 + baseWidth;
                    while (r.scrollWidth < minTotal) {
                        for (let i = 0; i < origCount; i++) r.appendChild(r.children[i].cloneNode(true));
                    }
                    return r;
                }

                function tick() {
                    if (!dragging && row) {
                        offset += SPEED;
                        if (baseWidth > 0) {
                            if (offset >= baseWidth) offset -= baseWidth;
                            row.style.transform = `translateX(${-offset}px)`;
                        }
                    }
                    raf = requestAnimationFrame(tick);
                }

                function setCategory(slug) {
                    if (raf) cancelAnimationFrame(raf);
                    const track = getTrack(slug) || getTrack(activeCategory) || allTracks()[0];
                    if (!track) return;
                    activeCategory = track.dataset.category;
                    frame.dataset.active = activeCategory;
                    allTracks().forEach(t => t.classList.toggle('active', t === track));
                    tabs.forEach(tab => {
                        const isActive = tab.dataset.category === activeCategory;
                        tab.classList.toggle('active', isActive);
                        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });
                    row = buildRow(track);
                    const orig = parseInt(track.dataset.origCount || '0', 10);
                    baseWidth = row ? sumWidth(row.children, Math.max(1, orig)) : 0;
                    offset = 0;
                    tick();
                }

                tabs.forEach(tab => tab.addEventListener('click', () => {
                    if (!tab.classList.contains('active')) setCategory(tab.dataset.category);
                }));

                frame.addEventListener('pointerdown', (e) => {
                    dragging = true;
                    startX = e.clientX;
                    startOffset = offset;
                    frame.setPointerCapture?.(e.pointerId);
                    frame.style.cursor = 'grabbing';
                });
                frame.addEventListener('pointermove', (e) => {
                    if (!dragging || !row) return;
                    const dx = e.clientX - startX;
                    offset = startOffset - dx;
                    if (baseWidth > 0) {
                        while (offset < 0) offset += baseWidth;
                        while (offset >= baseWidth) offset -= baseWidth;
                    }
                    row.style.transform = `translateX(${-offset}px)`;
                });
                ['pointerup','pointercancel','mouseleave'].forEach(evt => frame.addEventListener(evt, (e) => {
                    dragging = false;
                    frame.releasePointerCapture?.(e.pointerId);
                    frame.style.cursor = 'grab';
                }));

                let rszTimer = 0;
                window.addEventListener('resize', () => {
                    clearTimeout(rszTimer);
                    rszTimer = setTimeout(() => setCategory(activeCategory), 150);
                });

                setCategory(activeCategory || (allTracks()[0]?.dataset.category || ''));
            }
        })();
    </script>
</body>

</html>
