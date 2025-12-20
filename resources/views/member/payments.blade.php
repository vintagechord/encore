<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>결제 내역 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    :root { --bg:#050912; --fg:#e6edff; --muted:#97a6c9; --card:#0f1729; --card-alt:#152033; --border:#1f2b41; --accent:#6366f1; --accent-hover:#818cf8; }
    [data-theme="light"] { --bg:#f8fafc; --fg:#0f1729; --muted:#475569; --card:#ffffff; --card-alt:#f1f5f9; --border:#d7dce2; --accent:#4f46e5; --accent-hover:#4338ca; }
    body { margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, Apple SD Gothic Neo, Malgun Gothic, sans-serif; }
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
