<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>결제 상세 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:10px; }
    .thumb{ width:100%; aspect-ratio:4/3; border-radius:10px; border:1px solid var(--border); object-fit:cover; background:var(--card-alt); }
    .muted{ color:var(--muted); }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:600; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; border-color:var(--border); color:var(--fg); }
    .enc-modal{ position:fixed; inset:0; background:rgba(0,0,0,.55); display:none; align-items:center; justify-content:center; z-index:60; }
    .enc-modal[aria-hidden="false"]{ display:flex; }
    .enc-modal .win{ width:min(420px,90vw); background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; box-shadow:0 18px 40px rgba(0,0,0,.4); }
    .enc-modal .actions{ display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    @if(session('ok'))
      <div class="enc-modal" id="noticeModal" aria-hidden="false" role="dialog" aria-modal="true" aria-label="결제 안내">
        <div class="win">
          <h2 style="margin:0 0 6px; font-size:18px;">결제 안내</h2>
          <p class="muted" style="margin:0">{{ session('ok') }}</p>
          <div class="actions">
            <button class="btn ghost" type="button" id="noticeClose">닫기</button>
          </div>
        </div>
      </div>
    @endif
    <h1 style="margin:0 0 12px">결제 상세</h1>
    <section class="card">
      <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px">
        <div>
          <div class="muted">결제번호</div>
          <div><strong>#{{ $payment->id }}</strong></div>
        </div>
        <div>
          <div class="muted">결제금액</div>
          <div style="font-weight:800; font-size:18px">{{ number_format($payment->amount) }} {{ $payment->currency }}</div>
        </div>
        <div>
          <div class="muted">상태</div>
          <div>{{ $payment->status }}</div>
        </div>
        <div>
          <div class="muted">결제일시</div>
          <div>{{ optional($payment->paid_at ?? $payment->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</div>
        </div>
      </div>
    </section>

    @php 
      $opt = data_get($payment->meta, 'option'); 
      $artists = data_get($opt,'artists',[]);
      $intakeId = data_get($payment->meta, 'intake_id');
      $intake = $intakeId ? \App\Models\IntakeRequest::find($intakeId) : null;
    @endphp
    @if($intake)
    <section class="card" style="margin-top:12px">
      <h2 style="margin:0 0 8px">행사 정보</h2>
      <div style="display:flex;gap:16px;flex-wrap:wrap">
        <div>
          <div class="muted">일정</div>
          <div>{{ optional($intake->event_start)->format('Y-m-d') }} @if($intake->event_end && $intake->event_end != $intake->event_start) ~ {{ optional($intake->event_end)->format('Y-m-d') }} @endif</div>
        </div>
        <div>
          <div class="muted">총예산</div>
          <div>{{ $intake->budget_min?number_format($intake->budget_min):'?' }} ~ {{ $intake->budget_max?number_format($intake->budget_max):'?' }} 원</div>
        </div>
      </div>
    </section>
    @endif
    @if($opt)
    <section class="card" style="margin-top:12px">
      <h2 style="margin:0 0 8px">결제한 옵션 구성</h2>
      <div class="grid">
        @foreach($artists as $a)
          <div>
            @if(!empty($a['image']))
              <img class="thumb" src="{{ $a['image'] }}" alt="{{ $a['title'] }}" loading="lazy">
            @else
              <img class="thumb" alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='120'%3E%3Crect width='100%25' height='100%25' fill='%231a253d'/%3E%3C/svg%3E">
            @endif
            <div style="margin-top:6px"><strong>{{ $a['title'] }}</strong></div>
            @php $min=$a['fee_min']??null; $max=$a['fee_max']??null; @endphp
            <div class="muted">예상: {{ $min?number_format($min):'?' }} ~ {{ $max?number_format($max):'?' }} 원</div>
          </div>
        @endforeach
      </div>
    </section>
    @endif

    <section class="card" style="margin-top:12px">
      <h2 style="margin:0 0 8px">현금영수증</h2>
      <p class="muted" style="margin:0 0 8px">데모: 번호 입력 후 발급 버튼을 누르면 발급된 것으로 처리합니다.</p>
      <form id="cashForm" method="post" action="#">
        <input placeholder="휴대폰번호 또는 사업자번호" style="padding:10px;border-radius:8px;border:1px solid var(--border);background:var(--card-alt);color:var(--fg)">
        <button class="btn" type="submit">발급</button>
        <span class="muted" id="cashMsg" style="margin-left:8px; display:none">현금영수증이 발급되었습니다.</span>
      </form>
    </section>
  </main>
  @include('public.partials.footer')
  @if(session('ok'))
    <script>
      (function(){
        const modal = document.getElementById('noticeModal');
        const closeBtn = document.getElementById('noticeClose');
        const close = () => modal?.setAttribute('aria-hidden','true');
        closeBtn?.addEventListener('click', close);
        modal?.addEventListener('click', (e) => { if (e.target === modal) close(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
      })();
    </script>
  @endif
  <script>
    (function(){
      const form = document.getElementById('cashForm');
      const msg = document.getElementById('cashMsg');
      form?.addEventListener('submit', function(e){
        e.preventDefault();
        if (!msg) return;
        msg.style.display = 'inline';
        setTimeout(() => { msg.style.display = 'none'; }, 2400);
      });
    })();
  </script>
</body>
</html>
