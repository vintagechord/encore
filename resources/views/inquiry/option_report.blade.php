<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>옵션 리포트 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:24px 20px; }
    .head{ display:flex; justify-content:space-between; align-items:flex-end; gap:12px; }
    .muted{ color:var(--muted); }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; box-shadow: inset 0 1px 0 rgba(255,255,255,.02); }
    .grid{ display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:12px; }
    .a{ display:flex; flex-direction:column; gap:8px; }
    .thumb{ width:100%; aspect-ratio: 4/3; border-radius:10px; border:1px solid var(--border); object-fit:cover; background:var(--card-alt); }
    .price{ font-weight:700; }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:600; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; color:var(--fg); border-color:var(--border); }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <div class="head">
      <div>
        <h1 style="margin:0">{{ $option['label'] ?? ('옵션 '.(($index ?? 0)+1)) }}</h1>
        @php 
          // 토큰 없는 신규 플로우에서는 문의 입력 예산을 우선 표시
          $displayMin = isset($set) ? ($option['budget_min'] ?? null) : ($intake->budget_min ?? ($option['budget_min'] ?? null));
          $displayMax = isset($set) ? ($option['budget_max'] ?? null) : ($intake->budget_max ?? ($option['budget_max'] ?? null));
        @endphp
        <div class="muted">총 예산: {{ $displayMin?number_format($displayMin):'?' }} ~ {{ $displayMax?number_format($displayMax):'?' }} 원</div>
      </div>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        @php 
          if (isset($set)) {
            $backUrl = !empty($set->public_token)
              ? route('share.token', ['token'=>$set->public_token])
              : route('member.recommendations', ['intake'=>$intake->id]);
          } else {
            $backUrl = route('inquiry.select_options', ['intake'=>$intake->id, 'seed'=>$seed ?? null]);
          }
        @endphp
        <a class="btn ghost" href="{{ $backUrl }}">← 옵션 선택으로</a>
        <form id="orderForm" method="POST" action="{{ route('order.option.submit') }}" style="display:inline">
          @csrf
          @if(isset($set))
            @if(!empty($set->public_token))
              <input type="hidden" name="token" value="{{ $set->public_token }}">
            @else
              <input type="hidden" name="intake" value="{{ $intake->id }}">
            @endif
          @else
            <input type="hidden" name="intake" value="{{ $intake->id }}">
            <input type="hidden" name="seed" value="{{ $seed ?? '' }}">
          @endif
          <input type="hidden" name="idx" value="{{ $index ?? 0 }}">
          <button class="btn" type="submit">이 옵션으로 의뢰하기</button>
        </form>
      </div>
    </div>

    <section class="card" style="margin-top:12px">
      <h2 style="margin:0 0 10px">구성 아티스트</h2>
      <div class="grid">
        @foreach(($option['artists'] ?? []) as $a)
          <article class="a">
            @php $url = $a['artist_id'] ? route('artist.show', ['artist'=>$a['artist_id']]) : '#'; @endphp
            <a href="{{ $url }}" class="muted" aria-label="{{ $a['title'] }} 상세">
              @if(!empty($a['image']))
                <img class="thumb" src="{{ $a['image'] }}" alt="{{ $a['title'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
              @else
                <img class="thumb" alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='120'%3E%3Crect width='100%25' height='100%25' fill='%231a253d'/%3E%3C/svg%3E">
              @endif
            </a>
            <div><strong>{{ $a['title'] }}</strong></div>
            @php $min=$a['fee_min']??null; $max=$a['fee_max']??null; @endphp
            <div class="muted">예상: {{ $min?number_format($min):'?' }} ~ {{ $max?number_format($max):'?' }} 원</div>
          </article>
        @endforeach
      </div>
    </section>
  </main>
  @include('public.partials.footer')
  <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light')?'light':'dark';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
</body>
</html>
