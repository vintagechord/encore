<!doctype html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>문의 접수 완료 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <!-- 완료 페이지는 검색 노출 불필요 -->
  <meta name="robots" content="noindex,follow">
  <meta name="description" content="문의 접수가 완료되었습니다. 빠르게 후보를 검토하여 공유드리겠습니다.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">

  <style>
    :root {
      --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
        radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
        #0b090a;
      --fg: #f7f1e9;
      --muted: #b6a89a;
      --accent: #f3c652;
      --accent-hover: #f0b840;
      --card: #151012;
      --card-alt: #1b1316;
      --border: #2a1c22;
      --ok: #34d399;
      --font-sans: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
      --font-display: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
    }
    [data-theme="light"] {
      --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%),
        linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%);
      --fg: #2b1b1b;
      --muted: #6b5b53;
      --accent: #e3b648;
      --accent-hover: #d5a63b;
      --card: #fffdf8;
      --card-alt: #f6ecdd;
      --border: #e6d4c0;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
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

    main {
      flex: 1;
    }

    header {
      border-bottom: 1px solid var(--border);
      background: rgba(9, 14, 26, 0.92);
      backdrop-filter: saturate(180%) blur(12px);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .nav {
      max-width: 1120px;
      margin: 0 auto;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      color: inherit;
      text-decoration: none;
      font-weight: 700;
    }

    .container {
      max-width: 720px;
      margin: 0 auto;
      padding: 24px 20px;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
    }

    h1 {
      margin: 0 0 8px;
      font-size: 24px;
      letter-spacing: -0.01em;
    }

    .lead {
      margin: 0 0 14px;
      color: var(--muted);
    }

    .ok {
      color: var(--ok);
    }

    .row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 14px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 16px;
      border-radius: 12px;
      border: 1px solid var(--btn);
      font-weight: 700;
      cursor: pointer;
      background: var(--btn);
      color: var(--btn-text);
      text-decoration: none;
      transition: background .2s ease, border-color .2s ease;
    }

    .btn:hover {
      background: var(--btn-hover);
      border-color: var(--btn-hover);
    }

    .btn-ghost {
      background: transparent;
      color: var(--fg);
      border-color: var(--border);
    }

    .btn-ghost:hover {
      background: rgba(141, 31, 45, 0.12);
      color: var(--fg);
    }

    .muted {
      color: var(--muted);
      font-size: 13px;
    }

    .footer { border-top: 1px solid var(--border); margin-top: 24px; background: rgba(11, 9, 10, 0.75); }
    [data-theme="light"] .footer { background: rgba(255, 255, 255, 0.75); }

    .footer-inner {
      max-width: 1120px;
      margin: 0 auto;
      padding: 16px 20px;
      color: var(--muted);
      font-size: 13px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px;
    }

    .check {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(22, 163, 74, 0.12);
      border: 1px solid rgba(52, 211, 153, 0.35);
      color: var(--ok);
      margin-bottom: 12px;
    }
  </style>
</head>

<body>
  @include('public.partials.header')

  <main id="main">
    <div class="container">
      <section class="card" role="status" aria-live="polite">
        <div class="check" aria-hidden="true">
          <!-- 체크 아이콘 -->
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 6L9 17l-5-5" />
          </svg>
        </div>
        <h1>문의가 접수되었습니다.</h1>
        <p class="lead">
          보통 <strong>영업일 기준 1일 내</strong> 후보를 <strong>마이페이지의 추천셋</strong>에서 볼 수 있습니다.
        </p>

        <div class="row" role="group" aria-label="다음 작업">
          <a class="btn" href="{{ route('share.example') }}">
            <!-- 카드 아이콘 -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16v16H4z" />
              <path d="M4 9h16" />
            </svg>
            공유 예시 보기
          </a>
          <a class="btn btn-ghost" href="{{ route('inquiry.create') }}">
            <!-- 플러스 아이콘 -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 5v14M5 12h14" />
            </svg>
            추가 문의
          </a>
          <a class="btn btn-ghost" href="{{ url('/') }}">홈으로</a>
        </div>

        <p class="muted" style="margin-top:12px">
          오입력이나 수정 요청이 있으면 회신 메일로 바로 알려주세요. 업무시간 외 접수는 다음 영업일에 처리됩니다.
        </p>
      </section>
    </div>
  </main>

  @include('public.partials.footer')
</body>

</html>
