<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>결제 내역 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
  <style>
    :root { --bg: radial-gradient(1200px 520px at 12% -8%, rgba(243, 198, 82, 0.18), rgba(10, 10, 10, 0) 60%), radial-gradient(900px 480px at 92% 2%, rgba(255, 255, 255, 0.05), rgba(10, 10, 10, 0) 55%), #0a0a0a; --fg:#f7f4ee; --muted:#b4b0a8; --card:#121212; --card-alt:#191919; --border:#2a2a2a; --accent:#f3c652; --accent-hover:#e6b940; --font-sans:"Pretendard","Apple SD Gothic Neo","Malgun Gothic",sans-serif; }
    [data-theme="light"] { --bg: radial-gradient(980px 360px at 10% -6%, rgba(243, 198, 82, 0.14), rgba(255, 255, 255, 0) 60%), linear-gradient(180deg, #ffffff 0%, #f7f3ea 100%); --fg:#1c1b19; --muted:#6b655c; --card:#ffffff; --card-alt:#f7f3ea; --border:#e3ddd2; --accent:#f3c652; --accent-hover:#e6b940; }
    body { margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .muted{ color:var(--muted); }
    .row{ display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
    .list{ list-style:none; padding:0; margin:0; display:grid; gap:10px; }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <h1 style="margin:0 0 12px">결제 내역</h1>
    @if(count($payments) === 0)
      <section class="card"><p class="muted" style="margin:0">결제 내역이 없습니다.</p></section>
    @else
      <ul class="list">
        @foreach($payments as $p)
          <li class="card">
            <a href="{{ route('payment.show', ['payment'=>$p->id]) }}" style="text-decoration:none;color:inherit">
              <div class="row">
                <div>
                  @php $opt = data_get($p->meta,'option'); $rtype = is_array($opt)?($opt['request_type']??null):null; $label = $rtype==='one_day'?'1일 Set 추천안':($rtype==='direct'?'아티스트 맞춤형':($rtype?'1초 Set 추천안':null)); @endphp
                  <strong>{{ number_format($p->amount) }} {{ $p->currency }}</strong>
                  @if($label) <span class="muted">· {{ $label }}</span> @endif
                  <span class="muted">{{ $p->status }}</span>
                </div>
                <div class="muted">{{ optional($p->paid_at ?? $p->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</div>
              </div>
              @if($p->description)
                <div class="muted" style="margin-top:6px">{{ $p->description }}</div>
              @endif
            </a>
          </li>
        @endforeach
      </ul>
      <div style="margin-top:12px">{{ $payments->links() }}</div>
    @endif
  </main>
  @include('public.partials.footer')
  <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light')?'light':'dark';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
</body>
</html>
