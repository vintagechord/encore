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
    <meta name="theme-color" content="#0b090a">
    <meta name="color-scheme" content="dark light">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">

    <style>
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
            --hero-overlay: linear-gradient(180deg, rgba(10, 10, 10, 0.2) 0%, rgba(10, 10, 10, 0.75) 65%, rgba(10, 10, 10, 0.92) 100%);
            --hero-video-filter: saturate(1.05) contrast(1.02) brightness(0.62);
            --font-sans: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
            --font-display: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
        }

        /* Light theme overrides */
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
            --hero-overlay: linear-gradient(180deg, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0.25) 65%, rgba(255, 255, 255, 0.08) 100%);
            --hero-video-filter: saturate(1.12) contrast(1.08) brightness(0.98);
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
            font-family: var(--font-sans);
            color: var(--fg);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, .testi-title, .success-title {
            font-family: var(--font-display);
            letter-spacing: -0.01em;
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
            background: rgba(11, 9, 10, .88);
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
        .brand-logo {
            height: 24px;
            width: auto;
            display: block;
        }
        @media (max-width: 768px) {
            .brand-logo { height: 20px; }
        }

        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            color: #1b130f;
            background: rgba(243, 198, 82, 0.85);
            border: 1px solid rgba(243, 198, 82, 0.55);
            padding: 2px 8px;
            border-radius: 999px
        }
        [data-theme="light"] .badge {
            color: #fff;
            background: var(--accent-2);
            border-color: var(--accent-2);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap
        }

        .theme-toggle { display:inline-flex; align-items:center; justify-content:center; gap:8px; height:36px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); cursor:pointer; }
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
        [data-theme="light"] .hero { background: linear-gradient(180deg,#fff7e6 0%, #f4e2cc 100%); border-bottom:1px solid var(--border); }
        [data-theme="light"] .card { box-shadow: 0 12px 28px rgba(2, 6, 23, 0.06); }
        [data-theme="light"] .btn-ghost { background: #ffffff; border-color: #d9d4cc; color: #1c1b19; }
        [data-theme="light"] .btn-ghost:hover { background: var(--accent); border-color: var(--accent); color: var(--accent-text); }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid transparent;
            color: var(--muted);
            font-weight: 600;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }
        @media (max-width: 768px) {
            .nav-link { height: 34px; padding: 0 10px; }
        }

        .nav-link:hover {
            background: rgba(243, 198, 82, 0.14);
            color: var(--fg);
            border-color: rgba(243, 198, 82, 0.35);
        }

        .nav-link[aria-current="page"] {
            border-color: rgba(243, 198, 82, 0.4);
            background: rgba(243, 198, 82, 0.2);
            color: var(--fg);
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(1100px 420px at 12% -20%, rgba(243, 198, 82, 0.18) 0%, rgba(10, 10, 10, 0) 60%),
                radial-gradient(900px 360px at 90% 12%, rgba(255, 255, 255, 0.06) 0%, rgba(10, 10, 10, 0) 58%),
                linear-gradient(180deg, #0a0a0a 0%, #121212 100%);
            border-bottom: 1px solid var(--border);
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            padding: 72px 0 56px;
            display: grid;
            gap: 14px;
            justify-items: center;
            text-align: center;
        }

        .hero-media {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .hero-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--hero-overlay);
        }

        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: var(--hero-video-filter);
            transform: scale(1.02);
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-video { display: none; }
        }

        .hero-copy {
            display: grid;
            gap: 10px;
            justify-items: center;
            max-width: min(720px, 92vw);
        }

        .hero-title {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .hero-kicker {
            font-size: clamp(18px, 3.2vw, 28px);
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--muted);
            line-height: 1.25;
        }

        .hero-highlight {
            position: relative;
            display: inline-block;
            font-size: clamp(34px, 7vw, 64px);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.05;
            padding: 2px 8px;
            z-index: 0;
        }

        .hero-highlight::after {
            content: "";
            position: absolute;
            left: 6px;
            right: 6px;
            bottom: 8px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(243, 198, 82, 0.35), rgba(243, 198, 82, 0));
            z-index: -1;
        }

        [data-theme="light"] .hero-highlight::after {
            background: linear-gradient(90deg, rgba(243, 198, 82, 0.55), rgba(243, 198, 82, 0.15));
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            font-size: clamp(15px, 2.6vw, 18px);
        }

        #copy-desc {
            max-width: 480px;
        }
        @media (max-width: 768px) {
            #copy-desc { display: none; }
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
            border: 1px solid var(--btn);
            font-weight: 600;
            cursor: pointer;
            background: var(--btn);
            color: var(--btn-text);
            transition: transform .04s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease;
            user-select: none;
        }

        .btn:active {
            transform: translateY(1px)
        }
        .btn:hover {
            background: var(--btn-hover);
            border-color: var(--btn-hover);
            box-shadow: 0 10px 22px rgba(243, 198, 82, 0.3);
        }

        .btn-primary {
            background: var(--btn);
            color: var(--btn-text);
            border-color: var(--btn)
        }

        .btn-primary:hover {
            background: var(--btn-hover);
            border-color: var(--btn-hover);
            box-shadow: 0 10px 22px rgba(243, 198, 82, 0.35);
        }

        .btn-ghost {
            background: transparent;
            border-color: var(--border);
            color: var(--fg);
        }

        .btn-ghost:hover {
            background: rgba(243, 198, 82, 0.12);
            border-color: rgba(243, 198, 82, 0.35);
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

        /* ▼ Artist Showcase */
        .artist-showcase {
            padding: 18px 0 24px;
            border-top: 1px solid var(--border);
        }
        .artist-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }
        .artist-actions-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }
        @media (max-width: 768px) {
            .artist-head { align-items: flex-start; }
            .artist-actions-row {
                width: 100%;
                justify-content: space-between;
                flex-wrap: nowrap;
                gap: 6px;
            }
            .artist-controls { order: 2; }
            .artist-tabs { order: 1; }
            .artist-tabs { flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none; }
            .artist-tabs::-webkit-scrollbar { display: none; }
        }
        .artist-eyebrow {
            font-size: 12px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
            display: inline-flex;
        }
        .artist-title {
            margin: 0;
            font-size: clamp(20px, 3.2vw, 26px);
        }
        .artist-title a {
            color: inherit;
            text-decoration: none;
        }
        .artist-title a:hover { color: var(--accent-hover); }
        .artist-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .artist-controls { display:flex; gap:8px; }
        .artist-btn {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(243,198,82,0.08);
            color: var(--fg);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .2s ease, border-color .2s ease;
        }
        .artist-btn:hover { background: rgba(243,198,82,0.18); border-color: var(--chip-border); }
        [data-theme="light"] .artist-btn {
            background: #ffffff;
            border-color: #d9d4cc;
            color: #1c1b19;
        }
        [data-theme="light"] .artist-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--accent-text);
        }
        .artist-tab {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }
        .artist-tab.active {
            background: var(--btn);
            color: var(--btn-text);
            border-color: var(--btn);
        }
        [data-theme="light"] .artist-tab {
            background: #ffffff;
            border-color: #d9d4cc;
            color: #1c1b19;
        }
        .artist-marquee {
            display: none;
            position: relative;
            overflow-x: auto;
            border-radius: 18px;
            padding: 10px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(12, 12, 12, 0.75);
            scroll-snap-type: x proximity;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            cursor: grab;
            touch-action: pan-y;
            overscroll-behavior-x: contain;
            user-select: none;
            -webkit-user-select: none;
        }
        .artist-marquee.active { display: block; }
        .artist-marquee.is-dragging { cursor: grabbing; }
        .artist-marquee.is-dragging a { pointer-events: none; }
        .artist-marquee::-webkit-scrollbar { display:none; }
        .artist-track { display: flex; width: max-content; gap: 12px; align-items: stretch; }
        .artist-set { display: flex; gap: 12px; align-items: stretch; }
        .artist-card {
            width: clamp(140px, 18vw, 180px);
            flex: 0 0 auto;
            background: rgba(20,20,20,0.92);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 10px;
            color: var(--fg);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
            scroll-snap-align: center;
        }
        .artist-thumb {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .artist-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .artist-name {
            font-weight: 700;
            font-size: 14px;
        }
        .artist-meta {
            font-size: 12px;
            color: var(--muted);
        }
        [data-theme="light"] .artist-marquee {
            background: rgba(255,255,255,0.75);
            border-color: rgba(0,0,0,0.1);
        }
        [data-theme="light"] .artist-card {
            background: rgba(255,255,255,0.9);
            border-color: rgba(0,0,0,0.08);
            color: #1b1b1b;
        }

        /* ▼ Testimonials */
        .testimonials {
            padding: 16px 0 36px;
            border-top: 1px solid var(--border);
        }

        .testi-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .testi-eyebrow {
            font-size: 12px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
            display: inline-flex;
        }

        .testi-title {
            margin: 0;
            font-size: clamp(20px, 3.2vw, 26px);
        }

        .testi-marquee {
            position: relative;
            overflow-x: auto;
            border-radius: 18px;
            padding: 10px;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(14, 14, 14, 0.72);
            scroll-snap-type: x proximity;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            cursor: grab;
            touch-action: pan-y;
            overscroll-behavior-x: contain;
            user-select: none;
            -webkit-user-select: none;
        }
        [data-theme="light"] .testi-marquee { background: rgba(255,255,255,0.7); border-color: rgba(0,0,0,0.1); }
        .testi-marquee.is-dragging { cursor: grabbing; }
        .testi-marquee.is-dragging a { pointer-events: none; }
        .testi-marquee::-webkit-scrollbar { display:none; }

        .testi-controls {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }
        .testi-btn {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(20,20,20,0.6);
            color: var(--fg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s ease, border-color .2s ease, transform .12s ease;
        }
        .testi-btn:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.36);
            transform: translateY(-1px);
        }
        [data-theme="light"] .testi-btn {
            background: #ffffff;
            color: #1b1b1b;
            border-color: rgba(0,0,0,0.18);
        }

        .testi-track {
            display: flex;
            width: max-content;
            align-items: stretch;
            gap: 12px;
        }

        .testi-set {
            display: flex;
            gap: 12px;
            align-items: stretch;
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
            background: var(--btn);
            color: var(--btn-text);
            border-color: var(--btn);
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
            position: relative;
            background: rgba(232, 232, 232, 0.65);
            border: 1px solid rgba(0,0,0,0.18);
            border-radius: 16px;
            padding: 14px 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 8px;
            width: clamp(160px, 22vw, 220px);
            height: clamp(140px, 18vw, 175px);
            flex: 0 0 auto;
            color: #0b0b0b;
            box-shadow: 0 8px 18px rgba(0,0,0,.14);
            scroll-snap-align: center;
        }

        .t-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
            background: rgba(17,17,17,0.8);
            opacity: 0.9;
        }

        .t-kind {
            font-size: 11px;
            color: #ffffff;
            background: #111;
            border: 1px solid #111;
            border-radius: 999px;
            padding: 3px 8px;
            display: inline-flex;
            width: max-content
        }
        [data-theme="light"] .t-kind { background:#111; border-color:#111; color:#fff; }

        .t-title {
            margin: 0;
            font-size: 15px;
            color: #0b0b0b;
            font-weight: 700;
        }

        blockquote {
            margin: 0;
            color: #111;
            font-size: 13px;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .t-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            color: #333;
            font-size: 12px;
            margin-top: auto;
        }

        .stars {
            letter-spacing: 1px;
            color: #111;
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
            color: #1b130f;
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

    @include('public.partials.header')

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
            .bn-btn:hover{ background: rgba(243,198,82,.18); border-color: var(--chip-border); }
            [data-theme="light"] .bn-btn{ background:#ffffff; color:#1b1b1b; border-color:#d9d4cc; }
            [data-theme="light"] .bn-btn:hover{ background: var(--accent); border-color: var(--accent); color: var(--accent-text); }
            /* Floating close bar at bottom center */
            .bn-closebar{ position:absolute; left:50%; transform:translateX(-50%); bottom:8px; display:flex; justify-content:center; width:100%; pointer-events:none; z-index:2; }
            .bn-closebar .bn-toggle{ pointer-events:auto; }
            .bn-toggle{ display:inline-flex; align-items:center; gap:6px; background: rgba(243,198,82,.18); border:1px solid var(--chip-border); color: var(--fg); border-radius:999px; padding:6px 12px; cursor:pointer; box-shadow: inset 0 1px 0 rgba(255,255,255,.04); }
            .bn-toggle:hover{ background: rgba(243,198,82,.28); }
            /* Light theme: solid accent for clear contrast */
            [data-theme="light"] .bn-toggle{ background: var(--accent); border-color: var(--accent); color:#1b130f; }
            [data-theme="light"] .bn-toggle:hover{ background: var(--accent-hover); border-color: var(--accent-hover); color:#1b130f; }
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
            <div class="hero-media" aria-hidden="true">
                <video class="hero-video" autoplay muted loop playsinline preload="metadata" poster="{{ asset('videos/hero-poster.jpg') }}">
                    <source src="{{ asset('videos/hero.webm') }}" type="video/webm">
                    <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="container hero-inner">
                <div class="hero-copy">
                    <h1 id="home-title" class="hero-title">
                        <span class="hero-kicker">견적 비교부터 계약까지</span>
                        <span id="copy-title" class="hero-highlight">한 번에</span>
                    </h1>
                    <p id="copy-desc">
                        복잡한 커뮤니케이션 없이, 필요한 인원만 딱
                    </p>
                </div>
                <style>
                  .optgrid{
                    position: relative;
                    display: grid;
                    place-items: center;
                    margin-top: 18px;
                    min-height: 220px;
                    perspective: 1000px;
                  }
                  .optcard{
                    --slot: 0;
                    --shift: clamp(140px, 20vw, 260px);
                    --tilt: 10deg;
                    --opt-bg: var(--card);
                    --opt-border: var(--border);
                    --opt-ring: rgba(243,198,82,.35);
                    --opt-ring-soft: rgba(243,198,82,.2);
                    --opt-btn: var(--btn);
                    --opt-btn-hover: var(--btn-hover);
                    --opt-btn-text: var(--btn-text);
                    position: absolute;
                    width: min(320px, 90vw);
                    min-height: 182px;
                    border: 1px solid var(--opt-border);
                    border-radius: 18px;
                    background: var(--opt-bg);
                    padding: 18px 18px 14px;
                    cursor: pointer;
                    transition: transform .6s cubic-bezier(0.2,0.8,0.2,1), box-shadow .25s ease, border-color .25s ease, filter .25s ease;
                    transform: translateX(calc(var(--slot) * var(--shift))) rotateY(calc(var(--slot) * var(--tilt)));
                    box-shadow: 0 14px 28px rgba(0,0,0,.42);
                    text-decoration: none;
                    color: inherit;
                    filter: saturate(0.78) brightness(0.7);
                    transform-style: preserve-3d;
                    text-rendering: optimizeLegibility;
                    opacity: 0.7;
                  }
                  .optcard[data-mode="instant"]{
                    --opt-bg: linear-gradient(160deg, rgba(243,198,82,0.3), rgba(12,12,12,0.82));
                    --opt-border: rgba(243,198,82,0.4);
                  }
                  .optcard[data-mode="one_day"]{
                    --opt-bg: linear-gradient(160deg, rgba(255,255,255,0.08), rgba(10,10,10,0.88));
                    --opt-border: rgba(243,198,82,0.32);
                  }
                  .optcard[data-mode="direct"]{
                    --opt-bg: linear-gradient(160deg, rgba(243,198,82,0.22), rgba(10,10,10,0.9));
                    --opt-border: rgba(243,198,82,0.36);
                  }
                  [data-theme="light"] .optcard[data-mode="instant"]{
                    --opt-bg: linear-gradient(160deg, rgba(243,198,82,0.3), rgba(255,255,255,0.95));
                    --opt-border: rgba(243,198,82,0.5);
                  }
                  [data-theme="light"] .optcard[data-mode="one_day"]{
                    --opt-bg: linear-gradient(160deg, rgba(255,255,255,0.7), rgba(255,255,255,0.95));
                    --opt-border: rgba(243,198,82,0.4);
                  }
                  [data-theme="light"] .optcard[data-mode="direct"]{
                    --opt-bg: linear-gradient(160deg, rgba(243,198,82,0.22), rgba(255,255,255,0.95));
                    --opt-border: rgba(243,198,82,0.45);
                  }
                  .optcard::before{
                    content:"";
                    position:absolute;
                    left:50%;
                    bottom:-14px;
                    width:72%;
                    height:18px;
                    transform: translateX(-50%);
                    border-radius:999px;
                    background: radial-gradient(circle at center, rgba(243,198,82,.45), rgba(243,198,82,0) 70%);
                    opacity:0;
                    filter: blur(1px);
                    transition: opacity .25s ease, transform .25s ease;
                  }
                  .optcard::after{
                    content:"";
                    position:absolute;
                    inset:-8px;
                    border-radius:22px;
                    border:1px solid transparent;
                    opacity:0;
                    transition: opacity .25s ease;
                    pointer-events:none;
                  }
                  .optcard[data-slot="-1"]{ --slot:-1; z-index:1; }
                  .optcard[data-slot="0"]{
                    --slot:0;
                    z-index:3;
                    border-color: var(--opt-border);
                    filter: saturate(1) brightness(1.05);
                    box-shadow: 0 24px 50px rgba(0,0,0,.55);
                    min-height: 198px;
                    padding: 18px 18px 16px;
                    opacity: 1;
                  }
                  .optcard[data-slot="0"]::after{
                    border-color: var(--opt-ring);
                    box-shadow: 0 0 0 6px var(--opt-ring-soft);
                    opacity:1;
                  }
                  .optcard[data-slot="0"]::before{
                    opacity:1;
                    transform: translateX(-50%) scaleX(1.05);
                  }
                  .optcard[data-slot="1"]{ --slot:1; z-index:1; }
                  .optcard:hover{ border-color: var(--opt-border); }
                  .optcard:focus-visible{
                    outline:none;
                    border-color: var(--accent);
                    box-shadow: 0 0 0 3px rgba(243,198,82,.35), 0 22px 46px rgba(15,23,42,.38);
                  }
                  .optcard h3{ margin:0 0 6px; font-size: clamp(18px, 2.2vw, 22px); font-weight: 800; letter-spacing: -0.01em; text-align:center; }
                  .optcard[data-slot="0"] h3{ font-size: clamp(19px, 2.5vw, 24px); }
                  .optcard p{ margin:0 0 6px; color:var(--muted); font-size:14px; min-height:32px; text-align:center; }
                  .optcard .btn{
                    margin-top: 6px;
                    border:1px solid var(--opt-btn);
                    background: var(--opt-btn);
                    color: var(--opt-btn-text);
                    transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
                    align-self:center;
                  }
                  .optcard .btn:hover{
                    background: var(--opt-btn-hover);
                    border-color: var(--opt-btn-hover);
                    transform: translateY(-1px);
                    box-shadow: 0 10px 22px rgba(0,0,0,.35), 0 0 0 2px rgba(0,0,0,.12) inset;
                  }
                  .optcard-face{
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    text-align:center;
                    height:100%;
                    backface-visibility: hidden;
                  }
                  .optcard.is-spinning .optcard-face{
                    animation: optSpin .55s cubic-bezier(0.2,0.8,0.2,1);
                    transform-origin: center;
                  }
                  @keyframes optSpin{
                    0%{ transform: rotateY(-160deg); }
                    60%{ transform: rotateY(18deg); }
                    100%{ transform: rotateY(0); }
                  }
                  @media (max-width: 860px){
                    .optgrid{
                      position: relative;
                      display: flex;
                      gap: 14px;
                      min-height: auto;
                      perspective: none;
                      overflow-x: auto;
                      padding: 0 18px 12px;
                      scroll-snap-type: x mandatory;
                      scroll-padding: 0 18px;
                      justify-content: flex-start;
                      scrollbar-width: none;
                      -webkit-overflow-scrolling: touch;
                    }
                    .optgrid::-webkit-scrollbar{ display:none; }
                    .optcard{
                      position: relative;
                      flex: 0 0 84%;
                      min-width: 240px;
                      width: auto;
                      transform: none !important;
                      filter: none;
                      scroll-snap-align: center;
                    }
                    .optcard h3{ font-size: 16px; }
                    .optcard p{ font-size: 12px; min-height: 40px; }
                    .optcard .btn{ height: 34px; padding: 0 10px; font-size: 12px; border-radius: 10px; margin-top: 6px; }
                    .optcard::after{ display:none; }
                  }
                  @media (prefers-reduced-motion: reduce){
                    .optcard{ transition: none; }
                    .optcard.is-spinning .optcard-face{ animation: none; }
                  }
                </style>
                <div class="optgrid" id="homeOptGrid" role="tablist" aria-label="문의 옵션">
                  <div class="optcard active" data-mode="instant" data-slot="0" role="tab" aria-selected="true" tabindex="0">
                    <div class="optcard-face">
                      <h3>1초 Set</h3>
                      <p>옵션 입력 즉시 3가지 추천안 자동 생성. 마음에 들지 않으면 재생성 가능.</p>
                      <a class="btn" href="{{ route('inquiry.create', ['mode'=>'instant']) }}">추천셋 즉시 생성</a>
                    </div>
                  </div>
                  <div class="optcard" data-mode="one_day" data-slot="1" role="tab" aria-selected="false" tabindex="0">
                    <div class="optcard-face">
                      <h3>1일 Set</h3>
                      <p>요구사항을 작성해 보내주시면 관리자가 큐레이션한 3가지 셋을 1일 내 전달.</p>
                      <a class="btn" href="{{ route('inquiry.create', ['mode'=>'one_day']) }}">관리자의 추천셋</a>
                    </div>
                  </div>
                  <div class="optcard" data-mode="direct" data-slot="-1" role="tab" aria-selected="false" tabindex="0">
                    <div class="optcard-face">
                      <h3>아티스트 맞춤형</h3>
                      <p>원하는 아티스트를 지정해 섭외 요청하기.</p>
                      <a class="btn" href="{{ route('inquiry.create', ['mode'=>'direct']) }}">아티스트 지정 섭외</a>
                    </div>
                  </div>
                </div>
                <script>
                  (function(){
                    const grid = document.getElementById('homeOptGrid');
                    if(!grid) return;
                    const cards = Array.from(grid.querySelectorAll('.optcard'));
                    if(!cards.length) return;
                    let centerIndex = cards.findIndex(c => c.classList.contains('active'));
                    if(centerIndex < 0) centerIndex = 0;

                    function applySlots(){
                      const len = cards.length;
                      cards.forEach((card, idx) => {
                        let offset = idx - centerIndex;
                        if (offset > 1) offset -= len;
                        if (offset < -1) offset += len;
                        card.dataset.slot = String(offset);
                        const active = offset === 0;
                        card.classList.toggle('active', active);
                        card.setAttribute('aria-selected', active ? 'true' : 'false');
                      });
                    }

                    function spin(card){
                      card.classList.add('is-spinning');
                      card.addEventListener('animationend', () => {
                        card.classList.remove('is-spinning');
                      }, { once: true });
                    }

                    function setCenter(card){
                      const idx = cards.indexOf(card);
                      if (idx < 0 || idx === centerIndex) return;
                      centerIndex = idx;
                      spin(card);
                      applySlots();
                      if (window.matchMedia('(max-width: 860px)').matches) {
                        card.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                      }
                    }

                    grid.addEventListener('click', function(e){
                      const card = e.target.closest('.optcard');
                      if(!card) return;
                      if (e.target.closest('a.btn')) return;
                      setCenter(card);
                      e.preventDefault();
                    });

                    grid.addEventListener('keydown', function(e){
                      const card = e.target.closest('.optcard');
                      if(!card) return;
                      if (e.target.closest('a.btn')) return;
                      if (e.key === 'Enter' || e.key === ' ') {
                        setCenter(card);
                        e.preventDefault();
                        return;
                      }
                      if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                        const dir = e.key === 'ArrowLeft' ? -1 : 1;
                        centerIndex = (centerIndex + dir + cards.length) % cards.length;
                        const next = cards[centerIndex];
                        spin(next);
                        applySlots();
                        e.preventDefault();
                      }
                    });

                    let scrollTimer = 0;
                    grid.addEventListener('scroll', function(){
                      if (!window.matchMedia('(max-width: 860px)').matches) return;
                      clearTimeout(scrollTimer);
                      scrollTimer = window.setTimeout(() => {
                        const center = grid.scrollLeft + grid.clientWidth / 2;
                        let bestIdx = 0;
                        let bestDist = Infinity;
                        cards.forEach((card, idx) => {
                          const cardCenter = card.offsetLeft + card.offsetWidth / 2;
                          const dist = Math.abs(cardCenter - center);
                          if (dist < bestDist) {
                            bestDist = dist;
                            bestIdx = idx;
                          }
                        });
                        if (bestIdx !== centerIndex) {
                          centerIndex = bestIdx;
                          applySlots();
                        }
                      }, 80);
                    });

                    applySlots();
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

        <!-- ▼ 섭외 가능 아티스트 섹션 -->
        @php
            $artistTabs = [
                ['slug' => 'music', 'label' => '음악'],
                ['slug' => 'mc', 'label' => '사회(MC)'],
                ['slug' => 'dance', 'label' => '댄스'],
                ['slug' => 'performance', 'label' => '퍼포먼스'],
                ['slug' => 'plan', 'label' => '기획공연'],
                ['slug' => 'celebrity', 'label' => '셀럽'],
            ];
            $artistBuckets = [];
            try {
                if (class_exists(\App\Models\Artist::class) && \Illuminate\Support\Facades\Schema::hasTable('artists')) {
                    $disciplines = collect();
                    if (class_exists(\App\Models\Discipline::class) && \Illuminate\Support\Facades\Schema::hasTable('disciplines')) {
                        $disciplines = \App\Models\Discipline::whereIn('slug', collect($artistTabs)->pluck('slug')->all())->get()->keyBy('slug');
                    }
                    foreach ($artistTabs as $tab) {
                        $query = \App\Models\Artist::query();
                        if (\Illuminate\Support\Facades\Schema::hasColumn('artists', 'active')) {
                            $query->where('active', true);
                        }
                        if ($disciplines->has($tab['slug'])) {
                            $query->where('discipline_id', $disciplines[$tab['slug']]->id);
                        }
                        $artistBuckets[$tab['slug']] = $query->inRandomOrder()->take(12)->get();
                    }
                } else {
                    foreach ($artistTabs as $tab) {
                        $artistBuckets[$tab['slug']] = collect();
                    }
                }
            } catch (\Throwable $e) {
                foreach ($artistTabs as $tab) {
                    $artistBuckets[$tab['slug']] = collect();
                }
            }
        @endphp
        <section class="artist-showcase" aria-labelledby="artist-title">
            <div class="container">
                <div class="artist-head">
                    <div>
                        <span class="artist-eyebrow">LINEUP</span>
                        <h2 id="artist-title" class="artist-title"><a href="https://encore-unlh.onrender.com/artists">섭외 가능 아티스트</a></h2>
                    </div>
                    <div class="artist-actions-row">
                        <div class="artist-tabs" role="tablist" aria-label="섭외 가능 아티스트 분류">
                            @foreach($artistTabs as $idx => $tab)
                                <button
                                    class="artist-tab{{ $idx === 0 ? ' active' : '' }}"
                                    type="button"
                                    id="artist-tab-{{ $tab['slug'] }}"
                                    data-target="{{ $tab['slug'] }}"
                                    role="tab"
                                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                                    aria-controls="artist-panel-{{ $tab['slug'] }}"
                                >{{ $tab['label'] }}</button>
                            @endforeach
                        </div>
                        <div class="artist-controls" role="group" aria-label="섭외 가능 아티스트 이동">
                            <button class="artist-btn" type="button" id="artistPrev" aria-label="이전">‹</button>
                            <button class="artist-btn" type="button" id="artistNext" aria-label="다음">›</button>
                        </div>
                    </div>
                </div>
                @foreach($artistTabs as $idx => $tab)
                    @php $list = $artistBuckets[$tab['slug']] ?? collect(); @endphp
                    <div
                        class="artist-marquee{{ $idx === 0 ? ' active' : '' }}"
                        id="artist-panel-{{ $tab['slug'] }}"
                        role="tabpanel"
                        aria-labelledby="artist-tab-{{ $tab['slug'] }}"
                        data-key="{{ $tab['slug'] }}"
                    >
                        <div class="artist-track" data-key="{{ $tab['slug'] }}">
                            <div class="artist-set" role="list">
                                @forelse($list as $artist)
                                    @php
                                        $img = $artist->image_url ?? ($artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image ?? null));
                                    @endphp
                                    <a class="artist-card" href="{{ route('artist.show', ['artist'=>$artist->id]) }}" role="listitem">
                                        <div class="artist-thumb">
                                            @if($img)
                                                <img src="{{ $img }}" alt="{{ $artist->name }} 이미지" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                                            @else
                                                <img alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Crect width='100%25' height='100%25' fill='%231a1a1a'/%3E%3C/svg%3E">
                                            @endif
                                        </div>
                                        <div class="artist-name">{{ $artist->name ?? '아티스트' }}</div>
                                        <div class="artist-meta">{{ $tab['label'] }}</div>
                                    </a>
                                @empty
                                    <div class="artist-card" role="listitem">
                                        <div class="artist-thumb"></div>
                                        <div class="artist-name">준비중</div>
                                        <div class="artist-meta">{{ $tab['label'] }}</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ▼ 신규: 샘플 문의/후기 섹션 -->
        @php
            $fallbackTestimonials = collect([
                [
                    'kind' => '샘플 문의',
                    'title' => '대학 축제 · 힙합/R&B · 1,500만 원',
                    'body' => '9월 말 저녁 타임, 40분 내외 공연. 남녀 혼성 혹은 콜라보 가능 아티스트 위주로 후보 부탁드립니다.',
                    'meta' => '예상 응답: 익일 오전 후보 6팀',
                    'rating' => 4.8,
                ],
                [
                    'kind' => '후기',
                    'title' => '기업 세미나 애프터파티',
                    'body' => '내부 승인까지 시간이 촉박했는데, 링크로 후보 공유가 빨라서 결정이 쉬웠습니다. 예산 범위도 명확했어요.',
                    'meta' => '마케팅팀 B실장',
                    'rating' => 5.0,
                ],
                [
                    'kind' => '후기',
                    'title' => '리테일 팝업 기념 공연',
                    'body' => '타깃 연령대에 맞춘 추천이 정확했습니다. 공유 링크 회수/재발급으로 보안 걱정도 줄었어요.',
                    'meta' => '브랜드 매니저 K',
                    'rating' => 4.9,
                ],
            ])->map(fn($item) => (object) $item);
            $testiItems = (isset($testimonials) && $testimonials->isNotEmpty()) ? $testimonials : $fallbackTestimonials;
        @endphp
        <section class="testimonials" aria-labelledby="testi-title">
            <div class="container">
                <div class="testi-head">
                    <div>
                        <span class="testi-eyebrow">Samples</span>
                        <h2 id="testi-title" class="testi-title">샘플 문의/후기</h2>
                    </div>
                    <div class="testi-controls" role="group" aria-label="샘플 문의/후기 이동">
                        <button class="testi-btn" type="button" id="testiPrev" aria-label="이전">‹</button>
                        <button class="testi-btn" type="button" id="testiNext" aria-label="다음">›</button>
                    </div>
                </div>
                <div class="testi-marquee">
                    <div class="testi-track" id="testiTrack">
                        <div class="testi-set" role="list">
                            @foreach($testiItems as $t)
                                @php
                                    $rating = isset($t->rating) ? (float) $t->rating : null;
                                    $stars = $rating ? str_repeat('★', (int) round($rating)) : '';
                                @endphp
                                <article class="t-card" role="listitem" aria-label="{{ $t->title }}">
                                    <span class="t-kind" aria-label="유형">{{ $t->kind ?? '후기' }}</span>
                                    <h3 class="t-title">{{ $t->title }}</h3>
                                    <blockquote cite="#" aria-label="내용">{{ $t->body }}</blockquote>
                                    @if(!empty($t->meta) || $rating)
                                        <div class="t-meta">
                                            @if(!empty($t->meta))
                                                <span>{{ $t->meta }}</span>
                                            @endif
                                            @if($rating)
                                                <span class="stars" aria-label="별점 5점 만점 {{ number_format($rating, 1) }}점">
                                                    {{ $stars }}<span class="sr-only">{{ number_format($rating, 1) }}/5</span>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 홈 본문 FAQ 프리뷰 제거 (FAQ는 /faq 별도 페이지로 이동) --}}
    </main>

    @include('public.partials.footer')

    <script>
        (function() {
            const testiTrack = document.getElementById('testiTrack');
            const testiMarquee = document.querySelector('.testi-marquee');
            if (testiTrack && testiMarquee) {
                const prevBtn = document.getElementById('testiPrev');
                const nextBtn = document.getElementById('testiNext');
                let baseWidth = 0;
                const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                const buildTesti = () => {
                    const set = testiTrack.querySelector('.testi-set');
                    if (!set) return;
                    while (testiTrack.children.length > 1) testiTrack.removeChild(testiTrack.lastChild);

                    let safety = 0;
                    while (testiTrack.scrollWidth < testiMarquee.clientWidth * 2 && safety < 6) {
                        const clone = set.cloneNode(true);
                        clone.setAttribute('aria-hidden', 'true');
                        testiTrack.appendChild(clone);
                        safety += 1;
                    }
                    baseWidth = set.scrollWidth || 0;
                    if (baseWidth === 0) {
                        baseWidth = Math.max(1, Math.floor(testiTrack.scrollWidth / Math.max(1, testiTrack.children.length)));
                    }
                };

                buildTesti();
                window.addEventListener('load', buildTesti);
                let rszTimer = 0;
                window.addEventListener('resize', () => {
                    clearTimeout(rszTimer);
                    rszTimer = setTimeout(buildTesti, 150);
                });

                let paused = false;
                let isDragging = false;
                let startX = 0;
                let startScroll = 0;
                let resumeTimer = 0;
                const SPEED = 0.35; // px per frame @60fps

                const pause = () => {
                    paused = true;
                    clearTimeout(resumeTimer);
                };
                const scheduleResume = () => {
                    clearTimeout(resumeTimer);
                    resumeTimer = setTimeout(() => { paused = false; }, 1200);
                };

                const onPointerDown = (e) => {
                    isDragging = true;
                    pause();
                    startX = e.clientX;
                    startScroll = testiMarquee.scrollLeft;
                    testiMarquee.classList.add('is-dragging');
                    testiMarquee.setPointerCapture?.(e.pointerId);
                };
                const onPointerMove = (e) => {
                    if (!isDragging) return;
                    const delta = e.clientX - startX;
                    testiMarquee.scrollLeft = startScroll - delta;
                };
                const onPointerUp = (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    testiMarquee.classList.remove('is-dragging');
                    testiMarquee.releasePointerCapture?.(e.pointerId);
                    scheduleResume();
                };

                testiMarquee.addEventListener('pointerdown', onPointerDown);
                testiMarquee.addEventListener('pointermove', onPointerMove);
                testiMarquee.addEventListener('pointerup', onPointerUp);
                testiMarquee.addEventListener('pointerleave', onPointerUp);
                let touchActive = false;
                let touchStartX = 0;
                let touchStartY = 0;
                let touchScroll = 0;
                testiMarquee.addEventListener('touchstart', (e) => {
                    if (!e.touches || !e.touches[0]) return;
                    touchActive = true;
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    touchScroll = testiMarquee.scrollLeft;
                    pause();
                    testiMarquee.classList.add('is-dragging');
                }, { passive: true });
                testiMarquee.addEventListener('touchmove', (e) => {
                    if (!touchActive || !e.touches || !e.touches[0]) return;
                    const deltaX = e.touches[0].clientX - touchStartX;
                    const deltaY = e.touches[0].clientY - touchStartY;
                    if (Math.abs(deltaY) > Math.abs(deltaX)) {
                        touchActive = false;
                        testiMarquee.classList.remove('is-dragging');
                        scheduleResume();
                        return;
                    }
                    e.preventDefault();
                    testiMarquee.scrollLeft = touchScroll - deltaX;
                }, { passive: false });
                const onTouchEnd = () => {
                    touchActive = false;
                    testiMarquee.classList.remove('is-dragging');
                    scheduleResume();
                };
                testiMarquee.addEventListener('touchend', onTouchEnd);
                testiMarquee.addEventListener('touchcancel', onTouchEnd);
                testiMarquee.addEventListener('mouseenter', pause);
                testiMarquee.addEventListener('mouseleave', scheduleResume);
                testiMarquee.addEventListener('wheel', () => { pause(); scheduleResume(); }, { passive: true });
                testiMarquee.addEventListener('scroll', () => { pause(); scheduleResume(); }, { passive: true });

                const getStep = () => {
                    const card = testiMarquee.querySelector('.t-card');
                    if (!card) return 200;
                    const style = window.getComputedStyle(testiTrack);
                    const gap = parseFloat(style.columnGap || style.gap || '12') || 12;
                    return card.getBoundingClientRect().width + gap;
                };
                const stepScroll = (dir) => {
                    pause();
                    const step = getStep() * dir;
                    testiMarquee.scrollBy({ left: step, behavior: 'smooth' });
                    scheduleResume();
                };
                prevBtn?.addEventListener('click', () => stepScroll(-1));
                nextBtn?.addEventListener('click', () => stepScroll(1));

                let lastTs = 0;
                const tick = (ts) => {
                    if (!prefersReduced && !paused && !isDragging) {
                        if (!lastTs) lastTs = ts;
                        const delta = ts - lastTs;
                        lastTs = ts;
                        const step = SPEED * (delta / 16.67);
                        testiMarquee.scrollLeft += step;
                        if (baseWidth > 0 && testiMarquee.scrollLeft >= baseWidth) {
                            testiMarquee.scrollLeft -= baseWidth;
                        }
                    } else {
                        lastTs = ts;
                    }
                    requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            }

            const artistTabs = document.querySelectorAll('.artist-tab');
            const artistPanels = Array.from(document.querySelectorAll('.artist-marquee'));
            if (artistTabs.length && artistPanels.length) {
                const panelByKey = new Map(artistPanels.map(p => [p.dataset.key, p]));
                const artistPrev = document.getElementById('artistPrev');
                const artistNext = document.getElementById('artistNext');
                const setActiveArtist = (key) => {
                    artistTabs.forEach(tab => {
                        const active = tab.dataset.target === key;
                        tab.classList.toggle('active', active);
                        tab.setAttribute('aria-selected', active ? 'true' : 'false');
                    });
                    artistPanels.forEach(panel => {
                        panel.classList.toggle('active', panel.dataset.key === key);
                        if (panel.dataset.key === key) {
                            panel.scrollLeft = 0;
                        }
                    });
                };
                artistTabs.forEach(tab => {
                    tab.addEventListener('click', () => setActiveArtist(tab.dataset.target));
                });
                setActiveArtist(artistTabs[0]?.dataset.target);

                const prefersReducedArtist = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const marquees = [];
                artistPanels.forEach(panel => {
                    const track = panel.querySelector('.artist-track');
                    const set = panel.querySelector('.artist-set');
                    if (!track || !set) return;
                    const data = { panel, track, set, baseWidth: 0, paused: false, isDragging: false, startX: 0, startScroll: 0, resumeTimer: 0 };
                    data.scheduleResume = () => {
                        clearTimeout(data.resumeTimer);
                        data.resumeTimer = setTimeout(() => { data.paused = false; }, 1200);
                    };
                    data.build = () => {
                        while (track.children.length > 1) track.removeChild(track.lastChild);
                        let safety = 0;
                        while (track.scrollWidth < panel.clientWidth * 2 && safety < 6) {
                            const clone = set.cloneNode(true);
                            clone.setAttribute('aria-hidden', 'true');
                            track.appendChild(clone);
                            safety += 1;
                        }
                        data.baseWidth = set.scrollWidth || 0;
                    };
                    data.build();
                    panel.addEventListener('pointerdown', (e) => {
                        data.isDragging = true;
                        data.paused = true;
                        clearTimeout(data.resumeTimer);
                        data.startX = e.clientX;
                        data.startScroll = panel.scrollLeft;
                        panel.classList.add('is-dragging');
                        panel.setPointerCapture?.(e.pointerId);
                    });
                    panel.addEventListener('pointermove', (e) => {
                        if (!data.isDragging) return;
                        const delta = e.clientX - data.startX;
                        panel.scrollLeft = data.startScroll - delta;
                    });
                    const endDrag = (e) => {
                        if (!data.isDragging) return;
                        data.isDragging = false;
                        panel.classList.remove('is-dragging');
                        panel.releasePointerCapture?.(e.pointerId);
                        data.scheduleResume();
                    };
                    panel.addEventListener('pointerup', endDrag);
                    panel.addEventListener('pointerleave', endDrag);
                    let tActive = false;
                    let tStartX = 0;
                    let tStartY = 0;
                    let tScroll = 0;
                    panel.addEventListener('touchstart', (e) => {
                        if (!e.touches || !e.touches[0]) return;
                        tActive = true;
                        data.paused = true;
                        clearTimeout(data.resumeTimer);
                        tStartX = e.touches[0].clientX;
                        tStartY = e.touches[0].clientY;
                        tScroll = panel.scrollLeft;
                        panel.classList.add('is-dragging');
                    }, { passive: true });
                    panel.addEventListener('touchmove', (e) => {
                        if (!tActive || !e.touches || !e.touches[0]) return;
                        const deltaX = e.touches[0].clientX - tStartX;
                        const deltaY = e.touches[0].clientY - tStartY;
                        if (Math.abs(deltaY) > Math.abs(deltaX)) {
                            tActive = false;
                            panel.classList.remove('is-dragging');
                            data.scheduleResume();
                            return;
                        }
                        e.preventDefault();
                        panel.scrollLeft = tScroll - deltaX;
                    }, { passive: false });
                    const onTouchEndArtist = () => {
                        tActive = false;
                        panel.classList.remove('is-dragging');
                        data.scheduleResume();
                    };
                    panel.addEventListener('touchend', onTouchEndArtist);
                    panel.addEventListener('touchcancel', onTouchEndArtist);
                    panel.addEventListener('wheel', () => { data.paused = true; data.scheduleResume(); }, { passive: true });
                    panel.addEventListener('scroll', () => { data.paused = true; data.scheduleResume(); }, { passive: true });
                    marquees.push(data);
                });
                const pauseActive = () => {
                    const active = marquees.find(m => m.panel.classList.contains('active'));
                    if (!active) return;
                    active.paused = true;
                    clearTimeout(active.resumeTimer);
                    active.resumeTimer = setTimeout(() => { active.paused = false; }, 1200);
                };
                const stepActive = (dir) => {
                    const panel = document.querySelector('.artist-marquee.active');
                    if (!panel) return;
                    const card = panel.querySelector('.artist-card');
                    if (!card) return;
                    const gap = 12;
                    const step = card.getBoundingClientRect().width + gap;
                    pauseActive();
                    panel.scrollBy({ left: step * dir, behavior: 'smooth' });
                };
                artistPrev?.addEventListener('click', () => stepActive(-1));
                artistNext?.addEventListener('click', () => stepActive(1));
                let artistResizeTimer = 0;
                window.addEventListener('resize', () => {
                    clearTimeout(artistResizeTimer);
                    artistResizeTimer = setTimeout(() => { marquees.forEach(m => m.build()); }, 150);
                });
                const SPEED = 0.28;
                const tickArtist = () => {
                    if (!prefersReducedArtist) {
                        marquees.forEach(m => {
                            if (!m.panel.classList.contains('active')) return;
                            if (m.paused || m.isDragging) return;
                            m.panel.scrollLeft += SPEED;
                            if (m.baseWidth > 0 && m.panel.scrollLeft >= m.baseWidth) {
                                m.panel.scrollLeft -= m.baseWidth;
                            }
                        });
                    }
                    requestAnimationFrame(tickArtist);
                };
                requestAnimationFrame(tickArtist);
            }

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
