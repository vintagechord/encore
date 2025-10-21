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
    <meta name="color-scheme" content="dark">

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

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(7, 12, 24, .88);
            backdrop-filter: saturate(200%) blur(12px);
            border-bottom: 1px solid var(--border);
        }

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
        }

        .stories-track {
            display: none;
        }

        .stories-track.active {
            display: block;
        }

        .stories-slide {
            display: none;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .stories-slide.current {
            display: grid;
        }

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

        .stories-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            gap: 12px;
            color: var(--muted);
        }

        .stories-nav {
            display: flex;
            gap: 10px;
        }

        .stories-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--fg);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .2s ease, border-color .2s ease;
        }

        .stories-btn:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .stories-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

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
                @if($storyGroups->isNotEmpty())
                <a class="nav-link" href="#success-stories">성공 사례</a>
                @endif
                <a class="nav-link" href="{{ route('inquiry.create') }}">문의</a>
                <span class="badge" aria-label="베타 배지">BETA</span>
            </div>
        </div>
    </header>

    <main id="main" aria-live="polite">
        <section class="hero" aria-labelledby="home-title">
            <div class="container hero-inner">
                <h1 id="home-title"><span id="copy-title">행사에 딱 맞는 아티스트,<br>바로 추천받으세요.</span></h1>
                <p id="copy-desc">
                    간단한 요구사항만 알려주시면 예산·콘셉트·타깃에 맞춘 후보를 선별해
                    공유 링크로 전달합니다. 필요하면 언제든 새 링크로 회수·재발급도 가능해요.
                </p>
                <div class="cta" role="group" aria-label="주요 작업">
                    <a id="copy-cta" class="btn btn-primary" href="{{ route('inquiry.create') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="cta-text">문의하기</span>
                    </a>
                    <a class="btn btn-ghost" href="{{ url('/r/example') }}" aria-label="공유 예시 페이지 (샘플)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4z" />
                            <path d="M4 9h16" />
                        </svg>
                        공유 예시 보기
                    </a>
                </div>

                <div class="features" aria-label="핵심 기능 소개">
                    <article class="card">
                        <span class="ico" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                        </span>
                        <div>
                            <h3>간편한 문의</h3>
                            <p>핵심 정보 몇 가지만 입력하면 접수 완료, 진행 상황은 실시간으로 메일로 안내합니다.</p>
                        </div>
                    </article>

                    <article class="card">
                        <span class="ico" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </span>
                        <div>
                            <h3>빠른 추천</h3>
                            <p>요청 조건을 기반으로 MD가 후보를 큐레이션하고, 비교하기 쉬운 카드로 정리해 드립니다.</p>
                        </div>
                    </article>

                    <article class="card">
                        <span class="ico" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c7d2ff" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" x2="12" y1="15" y2="3" />
                            </svg>
                        </span>
                        <div>
                            <h3>링크로 공유</h3>
                            <p>링크로 안전하게 공유하고, 필요하면 즉시 회수·재발급으로 보안과 협업을 모두 잡습니다.</p>
                        </div>
                    </article>
                </div>

            </div>
        </section>

        @if($storyGroups->isNotEmpty())
        <section id="success-stories" class="success-stories" aria-labelledby="success-title">
            <div class="container">
                <div class="success-head">
                    <div>
                        <span class="success-eyebrow">SUCCESS STORIES</span>
                        <h2 id="success-title" class="success-title">최근 섭외 성공 사례</h2>
                        <p class="muted" style="margin:8px 0 0; max-width:420px;">실제 고객 프로젝트 중 공개 가능한 일부만 소개합니다. 관리자에서 직접 발행·숨김을 관리할 수 있습니다.</p>
                    </div>
                    @if($storyGroups->count() > 1)
                    <div class="success-tabs" role="tablist">
                        @foreach($storyGroups->keys() as $idx => $group)
                            @php $slug = Str::slug($group ?: 'story'); @endphp
                            <button class="success-tab{{ $idx === 0 ? ' active' : '' }}" data-category="{{ $slug }}" role="tab" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">{{ $group }}</button>
                        @endforeach
                    </div>
                    @endif
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

        <section aria-label="FAQ 프리뷰">
            <div class="container" style="padding:24px 0 12px;">
                <details style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:10px">
                    <summary style="cursor:pointer;font-weight:600;color:var(--fg)">견적은 어떻게 산정되나요?</summary>
                    <div style="margin-top:10px;color:var(--muted);font-size:14px">예산, 일정, 행사 성격 등을 고려해 범위를 제안드리고, 확정 시 상세 견적을 제공합니다.</div>
                </details>
                <details style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:10px">
                    <summary style="cursor:pointer;font-weight:600;color:var(--fg)">추천 결과는 어디서 볼 수 있나요?</summary>
                    <div style="margin-top:10px;color:var(--muted);font-size:14px">전용 공개 페이지 링크로 전달됩니다. 필요하면 링크를 회수하거나 재발급할 수 있어요.</div>
                </details>
            </div>
        </section>
    </main>

    <footer class="footer" role="contentinfo">
        <div class="container footer-inner">
            <span>&copy; {{ date('Y') }} Encore</span>
            <div style="display:flex;gap:12px;align-items:center">
                <a href="{{ route('inquiry.create') }}" class="btn btn-ghost" style="padding:8px 10px">문의하기</a>
            </div>
        </div>
    </footer>

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

            const frame = document.querySelector('.stories-frame');
            if (frame) {
                const tabs = document.querySelectorAll('.success-tab');
                const btnPrev = document.querySelector('.stories-btn[data-dir="prev"]');
                const btnNext = document.querySelector('.stories-btn[data-dir="next"]');
                const indicator = document.querySelector('.stories-indicator strong');
                const totalEl = document.querySelector('.stories-total');

                let activeCategory = frame.dataset.active || '';
                let currentPage = 0;

                const getTrack = (slug) => frame.querySelector(`.stories-track[data-category="${slug}"]`);
                const allTracks = () => Array.from(frame.querySelectorAll('.stories-track'));

                function updateButtons(track) {
                    const total = parseInt(track?.dataset.pages || '1', 10);
                    if (btnPrev) btnPrev.disabled = currentPage <= 0;
                    if (btnNext) btnNext.disabled = currentPage >= total - 1;
                }

                function setCategory(slug) {
                    const track = getTrack(slug) || getTrack(activeCategory) || allTracks()[0];
                    if (!track) return;
                    activeCategory = track.dataset.category;
                    frame.dataset.active = activeCategory;

                    allTracks().forEach(t => t.classList.toggle('active', t === track));
                    const slides = Array.from(track.querySelectorAll('.stories-slide'));
                    slides.forEach((slide, idx) => slide.classList.toggle('current', idx === 0));
                    currentPage = 0;
                    if (indicator) indicator.textContent = '1';
                    if (totalEl) totalEl.textContent = track.dataset.pages || '1';
                    tabs.forEach(tab => {
                        const isActive = tab.dataset.category === activeCategory;
                        tab.classList.toggle('active', isActive);
                        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });
                    updateButtons(track);
                }

                function move(delta) {
                    const track = getTrack(activeCategory);
                    if (!track) return;
                    const slides = Array.from(track.querySelectorAll('.stories-slide'));
                    const total = parseInt(track.dataset.pages || slides.length || 1, 10);
                    const next = Math.min(Math.max(currentPage + delta, 0), total - 1);
                    if (next === currentPage) return;
                    slides[currentPage]?.classList.remove('current');
                    slides[next]?.classList.add('current');
                    currentPage = next;
                    if (indicator) indicator.textContent = String(currentPage + 1);
                    updateButtons(track);
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        if (tab.classList.contains('active')) return;
                        setCategory(tab.dataset.category);
                    });
                });

                btnPrev?.addEventListener('click', () => move(-1));
                btnNext?.addEventListener('click', () => move(1));

                setCategory(activeCategory || (allTracks()[0]?.dataset.category || ''));
            }
        })();
    </script>
</body>

</html>
