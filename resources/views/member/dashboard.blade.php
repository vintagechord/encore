<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>내 페이지 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    /* Use shared theme variables from public/partials/header */
    body { margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:24px 20px; }
    .grid{ display:grid; grid-template-columns:1fr; gap:12px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .muted{ color:var(--muted); }
    a{ color:inherit; text-decoration:none; }
    a:hover{ color:var(--accent-hover); text-decoration:underline; }
    .row{ display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
    .btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; height:36px; padding:0 12px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:600; line-height:1; box-sizing:border-box; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; color:var(--fg); border-color:var(--border); }
    .status-btn{ min-width:108px; justify-content:center; text-align:center; }
    /* pagination */
    .pager{ display:flex; justify-content:center; margin-top:12px; }
    .pager nav{ display:inline-flex; gap:6px; align-items:center; background:var(--card); border:1px solid var(--border); border-radius:999px; padding:6px; }
    .pager nav a, .pager nav span{ display:inline-flex; min-width:34px; height:34px; padding:0 10px; align-items:center; justify-content:center; border-radius:999px; border:1px solid transparent; color:var(--fg); text-decoration:none; }
    .pager nav a:hover{ border-color:var(--chip-border); background:var(--card-alt); }
    .pager nav span[aria-current="page"], .pager nav .active{ background:var(--btn); color:var(--btn-text); border-color:var(--btn); }
    .pager nav .disabled{ opacity:.45; cursor:not-allowed; }
    /* mobile tweaks */
    @media (max-width: 640px){
      .enc-container{ padding:16px 14px; }
      .card{ border-radius:12px; padding:14px; }
      .row{ gap:8px; }
      .btn{ height:34px; padding:0 10px; border-radius:10px; line-height:1; }
    }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <style>
      .subtabs{ display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
      .subtabs .tab{ display:inline-flex; align-items:center; height:34px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); text-decoration:none; font-weight:600; }
      .subtabs .tab:hover{ background: var(--card-alt); border-color: var(--chip-border); }
      .subtabs .tab.active{ background: var(--btn); border-color: var(--btn); color:var(--btn-text); }
    </style>
    @php $t = request()->query('type') ?: 'instant'; @endphp
    <nav class="subtabs" aria-label="추천셋 세부 분류">
      <a class="tab {{ $t==='instant' ? 'active' : '' }}" href="{{ route('member.dashboard',['type'=>'instant']) }}">1초 Set</a>
      <a class="tab {{ $t==='one_day' ? 'active' : '' }}" href="{{ route('member.dashboard',['type'=>'one_day']) }}">1일 Set</a>
      <a class="tab {{ $t==='direct' ? 'active' : '' }}" href="{{ route('member.dashboard',['type'=>'direct']) }}">아티스트 문의</a>
    </nav>
    <div class="grid">
      <section class="card">
        <div class="row">
          <h2 style="margin:0">최근 문의</h2>
        </div>
        @if(count($intakes) === 0)
          <p class="muted" style="margin:8px 0 0">아직 문의 기록이 없습니다. 공개 페이지에서 문의를 남겨보세요.</p>
        @else
          <ul style="list-style:none;padding:0;margin:8px 0 0;display:grid;gap:8px">
            @foreach($intakes as $inq)
              @php $set = $inq->latestSet; $notes=json_decode((string)$inq->notes,true); $rtype=$notes['request_type'] ?? ($inq->category==='direct'?'direct':'instant');
                  $label = $rtype==='one_day' ? '1일 Set 추천안' : ($rtype==='direct' ? '아티스트 맞춤형' : '1초 Set 추천안'); @endphp
              <li class="card" style="padding:12px">
                <div class="row">
                  <div>
                    @php
                      // 목적지: 옵션 의뢰는 추천안 페이지로, 1일 Set은 접수 확인으로, direct는 링크 없음
                      if ($rtype==='one_day') {
                        $titleUrl = route('inquiry.thanks');
                      } elseif ($rtype==='direct') {
                        $titleUrl = null;
                      } else {
                        $titleUrl = $set
                          ? (!empty($set->public_token)
                              ? route('share.token', ['token'=>$set->public_token])
                              : route('member.recommendations', ['intake'=>$inq->id]))
                          : route('inquiry.select_options', ['intake'=>$inq->id]);
                      }
                    @endphp
                    @if($titleUrl)
                      <a href="{{ $titleUrl }}" style="text-decoration:none;color:inherit">
                        <strong>#{{ $inq->id }}</strong> <span class="muted">{{ $label }}</span>
                        <span class="muted">{{ optional($inq->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
                      </a>
                    @else
                      <div>
                        <strong>#{{ $inq->id }}</strong> <span class="muted">{{ $label }}</span>
                        <span class="muted">{{ optional($inq->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
                      </div>
                    @endif
                  </div>
                  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    @php
                      // 대시보드는 '추천안 보기'만 제공. 추천셋이 없으면 버튼 없음.
                      $btnUrl = ($rtype==='direct') ? null : (
                        $set
                          ? (!empty($set->public_token)
                              ? route('share.token', ['token'=>$set->public_token])
                              : route('member.recommendations', ['intake'=>$inq->id]))
                          : null
                      );
                    @endphp
                    @if($btnUrl)
                      <a class="btn status-btn" href="{{ $btnUrl }}">추천안 보기</a>
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          <div class="pager">{{ $intakes->links('pagination::encore') }}</div>
        @endif
      </section>

      {{-- 최근 결제 섹션은 의뢰내역 페이지 하단으로 이동했습니다. --}}
    </div>
  </main>
  @include('public.partials.footer')
  <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light')?'light':'dark';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
</body>
</html>
