<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>결제 내역 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
  <style>
    :root { --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%), radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%), #0b090a; --fg:#f7f1e9; --muted:#b6a89a; --card:#151012; --card-alt:#1b1316; --border:#2a1c22; --accent:#f3c652; --accent-hover:#f0b840; --font-sans:"Noto Sans KR","Apple SD Gothic Neo","Malgun Gothic",sans-serif; }
    [data-theme="light"] { --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%), linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%); --fg:#2b1b1b; --muted:#6b5b53; --card:#fffdf8; --card-alt:#f6ecdd; --border:#e6d4c0; --accent:#e3b648; --accent-hover:#d5a63b; }
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
