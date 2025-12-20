<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>{{ $artist->name ?? $artist->stage_name }} | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:960px; margin:0 auto; padding:24px 20px; }
    .grid{ display:grid; grid-template-columns:1fr; gap:16px; }
    @media(min-width:900px){ .grid{ grid-template-columns: 360px 1fr; } }
    .thumb{ width:100%; aspect-ratio: 4/3; border-radius:12px; border:1px solid var(--border); object-fit:cover; background:var(--card-alt); }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .muted{ color:var(--muted); }
    .badge{ display:inline-flex; align-items:center; gap:6px; padding:4px 8px; border:1px solid var(--chip-border); border-radius:999px; background:var(--chip); }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:600; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <div class="grid">
      <div>
        @php
          $img = $artist->image_url ?? ($artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image ?? null));
        @endphp
        @if($img)
          <img class="thumb" src="{{ $img }}" alt="{{ $artist->name }} 이미지" loading="lazy" decoding="async" referrerpolicy="no-referrer">
        @else
          <img class="thumb" alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='360' height='270'%3E%3Crect width='100%25' height='100%25' fill='%231a253d'/%3E%3C/svg%3E">
        @endif
      </div>
      <div class="card">
        <h1 style="margin:0 0 8px">{{ $artist->name }}</h1>
        @php $fee=$artist->fee_range; @endphp
        @if($fee)
          <div class="muted">예상 섭외비: {{ $fee['min']?number_format($fee['min']):'?' }} ~ {{ $fee['max']?number_format($fee['max']):'?' }} 원</div>
        @endif
        @if(is_array($artist->genres))
          <div style="margin-top:8px; display:flex; gap:6px; flex-wrap:wrap">
            @foreach($artist->genres as $g)<span class="badge">{{ $g }}</span>@endforeach
          </div>
        @endif
        @if($artist->notes)
          <p class="muted" style="margin-top:10px; white-space:pre-wrap">{{ $artist->notes }}</p>
        @endif
        <div style="margin-top:12px">
          <a class="btn" href="{{ route('inquiry.create') }}">문의하기</a>
        </div>
      </div>
    </div>
  </main>
  @include('public.partials.footer')
</body>
</html>
