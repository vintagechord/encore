<!doctype html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>문의 접수 완료 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 완료 페이지는 검색 노출 불필요 -->
  <meta name="robots" content="noindex,follow">
  <meta name="description" content="문의 접수가 완료되었습니다. 빠르게 후보를 검토하여 공유드리겠습니다.">

  <style>
    :root {
      --bg: #050912;
      --fg: #e6edff;
      --muted: #96a6c6;
      --accent: #6366f1;
      --accent-hover: #818cf8;
      --card: #0f1729;
      --card-alt: #151f33;
      --border: #1f2b41;
      --ok: #34d399;
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
      font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, Apple SD Gothic Neo, Malgun Gothic, sans-serif;
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
      border: 1px solid var(--accent);
      font-weight: 700;
      cursor: pointer;
      background: var(--accent);
      color: #fff;
      text-decoration: none;
      transition: background .2s ease, border-color .2s ease;
    }

    .btn:hover {
      background: var(--accent-hover);
      border-color: var(--accent-hover);
    }

    .btn-ghost {
      background: transparent;
      color: var(--fg);
      border-color: var(--border);
    }

    .btn-ghost:hover {
      background: rgba(99, 102, 241, 0.16);
      color: var(--accent-hover);
    }

    .muted {
      color: var(--muted);
      font-size: 13px;
    }

    .footer {
      border-top: 1px solid var(--border);
      margin-top: 24px;
      background: rgba(9, 14, 26, 0.75);
    }

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
  <header>
    <nav class="nav" aria-label="상단 내비게이션">
      <a class="brand" href="{{ url('/') }}" aria-label="Encore 홈">
        <span aria-hidden="true" style="display:inline-flex;width:20px;height:20px;border-radius:6px;background:linear-gradient(135deg,#6366f1,#ec4899);"></span>
        Encore
      </a>
      <span class="muted" aria-hidden="true">/ 문의 완료</span>
    </nav>
  </header>

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
          보통 <strong>영업일 기준 1일 내</strong> 후보를 정리해 <span class="ok">공유 링크</span>로 보내드립니다.
          확인 메일도 함께 발송했으니, <strong>수신함/스팸함</strong>을 확인해주세요.
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

  <footer class="footer" role="contentinfo">
    <div class="footer-inner">
      <span>&copy; {{ date('Y') }} Encore</span>
    </div>
  </footer>
</body>

</html>
