<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>About | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:28px 20px 44px; }
    .about-hero{ padding: 18px 0 22px; }
    .about-kicker{ font-size:12px; letter-spacing:.22em; text-transform:uppercase; color:var(--muted); display:inline-flex; gap:8px; align-items:center; }
    .about-kicker::after{ content:""; width:32px; height:1px; background:var(--accent); opacity:.6; display:inline-block; }
    .about-title{ margin:10px 0 8px; font-size: clamp(28px, 4.2vw, 44px); font-family: var(--font-display); letter-spacing:-0.02em; }
    .about-desc{ margin:0; color:var(--muted); font-size:15px; line-height:1.75; max-width:720px; }
    .about-grid{ display:grid; gap:14px; margin-top:22px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .about-card{ background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px; min-height:180px; display:flex; flex-direction:column; gap:10px; }
    .about-card h3{ margin:0; font-size:18px; font-family: var(--font-display); }
    .about-card p{ margin:0; color:var(--muted); font-size:14px; line-height:1.65; }
    .about-cta{ display:flex; gap:10px; flex-wrap:wrap; margin-top:20px; }
    .btn{ display:inline-flex; align-items:center; justify-content:center; height:38px; padding:0 14px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:700; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; border-color:var(--border); color:var(--fg); }
    @media (max-width: 640px){
      .enc-container{ padding:22px 18px 36px; }
      .about-card{ min-height:auto; }
    }
  </style>
</head>
<body>
  @include('public.partials.header', ['hideMemberNav' => true])
  <main class="enc-container">
    <section class="about-hero">
      <span class="about-kicker">About Encore</span>
      <h1 class="about-title">회사 소개</h1>
      <p class="about-desc">
        Encore는 행사 목적과 예산, 타깃에 맞는 아티스트를 빠르게 연결하는 섭외 플랫폼입니다.
        이 페이지는 회사 소개 템플릿으로, 실제 내용은 추후 업데이트 예정입니다.
      </p>
      <div class="about-cta">
        <a class="btn" href="{{ route('inquiry.create') }}">문의 시작하기</a>
        <a class="btn ghost" href="{{ route('home') }}">홈으로</a>
      </div>
    </section>

    <section class="about-grid" aria-label="Encore 소개">
      <article class="about-card">
        <h3>미션</h3>
        <p>아티스트 섭외 과정을 투명하고 간결하게 만들어, 기획자와 아티스트 모두가 빠르게 연결되도록 돕습니다.</p>
      </article>
      <article class="about-card">
        <h3>프로세스</h3>
        <p>요구사항 입력 → 추천셋 생성 → 공유 링크 전달 → 협의 및 결제까지 한 흐름으로 관리합니다.</p>
      </article>
      <article class="about-card">
        <h3>팀</h3>
        <p>공연 제작, 마케팅, 아티스트 매니지먼트 경험을 가진 멤버들이 함께 운영하고 있습니다.</p>
      </article>
    </section>
  </main>
  @include('public.partials.footer')
</body>
</html>
