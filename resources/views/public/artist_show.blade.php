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
    .section-title{ margin:16px 0 6px; font-size:16px; font-weight:700; }
    .info-grid{ display:grid; gap:10px; margin-top:10px; }
    .info-row{ display:flex; flex-wrap:wrap; gap:10px; font-size:13px; color:var(--muted); }
    .info-row span{ display:inline-flex; align-items:center; gap:6px; }
    .list{ margin:6px 0 0; padding-left:16px; color:var(--muted); font-size:13px; line-height:1.6; }
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
        @php
          $intro = $artist->bio ?? data_get($artist->meta ?? [], 'intro') ?? $artist->notes ?? null;
          $filmographyRaw = data_get($artist->meta ?? [], 'filmography') ?? null;
          $filmography = [];
          if (is_string($filmographyRaw)) {
            $filmography = preg_split('/\\r\\n|\\n|,/', $filmographyRaw);
          } elseif (is_array($filmographyRaw)) {
            $filmography = $filmographyRaw;
          }
          $filmography = array_values(array_filter(array_map('trim', $filmography)));
        @endphp
        <h1 style="margin:0 0 8px">{{ $artist->name }}</h1>
        @if($artist->discipline?->name)
          <div class="badge" style="margin-bottom:8px;">{{ $artist->discipline->name }}</div>
        @endif
        @php $fee=$artist->fee_range; @endphp
        @if($fee)
          <div class="muted">예상 섭외비: {{ $fee['min']?number_format($fee['min']):'?' }} ~ {{ $fee['max']?number_format($fee['max']):'?' }} 원</div>
        @endif
        @if(is_array($artist->genres))
          <div style="margin-top:8px; display:flex; gap:6px; flex-wrap:wrap">
            @foreach($artist->genres as $g)<span class="badge">{{ $g }}</span>@endforeach
          </div>
        @endif
        @if($intro)
          <div class="section-title">소개</div>
          <p class="muted" style="margin-top:0; white-space:pre-wrap">{{ $intro }}</p>
        @endif
        @if(!empty($filmography))
          <div class="section-title">필모그래피</div>
          <ul class="list">
            @foreach($filmography as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
        <div class="info-grid">
          <div class="info-row">
            @if($artist->home_city)
              <span>지역: {{ $artist->home_city }}</span>
            @endif
            @if($artist->stage_name && $artist->name !== $artist->stage_name)
              <span>활동명: {{ $artist->stage_name }}</span>
            @endif
          </div>
        </div>
        <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
          <a class="btn" href="{{ route('direct.request.create', ['requested' => $artist->name ?? $artist->stage_name]) }}">아티스트 지정섭외</a>
          <a class="btn" style="background:transparent;border-color:var(--border);color:var(--fg);" href="{{ route('inquiry.create') }}">일반 문의</a>
        </div>
      </div>
    </div>
  </main>
  @include('public.partials.footer')
</body>
</html>
