<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>나의 아티스트 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body { margin: 0; background: var(--bg); color: var(--fg); font-family: var(--font-sans); }
    .enc-container { max-width: 1120px; margin: 0 auto; padding: 24px 20px 56px; }
    .fav-head { display: grid; gap: 8px; margin-bottom: 18px; }
    .fav-head h1 { margin: 0; font-size: clamp(26px, 3.4vw, 36px); letter-spacing: -0.02em; }
    .fav-head p { margin: 0; color: var(--muted); max-width: 520px; }
    .fav-eyebrow { font-size: 12px; letter-spacing: 0.28em; text-transform: uppercase; color: var(--accent); font-weight: 700; }

    .artist-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .artist-card { display: grid; grid-template-columns: 140px 1fr; gap: 14px; padding: 14px; background: rgba(21,16,18,0.88); border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 18px 30px rgba(10,10,10,0.18); }
    [data-theme="light"] .artist-card { background: rgba(255,255,255,0.9); }
    .artist-media { display: block; width: 100%; aspect-ratio: 4/3; border-radius: 12px; overflow: hidden; background: var(--card-alt); }
    .artist-media img { width: 100%; height: 100%; object-fit: cover; }
    .artist-body h3 { margin: 0 0 6px; font-size: 18px; }
    .artist-body h3 a { color: inherit; text-decoration: none; }
    .artist-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; border: 1px solid var(--chip-border); background: var(--chip); font-size: 12px; color: var(--fg); }
    .artist-desc { margin: 6px 0 10px; color: var(--muted); font-size: 13px; }
    .artist-meta { display: flex; flex-wrap: wrap; gap: 8px; font-size: 12px; color: var(--muted); }
    .artist-actions { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
    .artist-actions .btn { display: inline-flex; align-items: center; justify-content: center; height: 36px; padding: 0 12px; border-radius: 10px; border: 1px solid var(--btn); background: var(--btn); color: var(--btn-text); text-decoration: none; font-weight: 600; }
    .artist-actions .btn:hover { background: var(--btn-hover); border-color: var(--btn-hover); }
    .artist-actions .btn.ghost { background: transparent; color: var(--fg); border-color: var(--border); }
    .artist-actions .btn.fav { background: transparent; border-color: var(--chip-border); color: var(--fg); }
    .artist-actions form { margin: 0; }
    .empty-state { padding: 32px; border: 1px dashed var(--border); border-radius: 16px; color: var(--muted); text-align: center; }

    @media (max-width: 1200px) {
      .artist-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 980px) {
      .artist-card { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  @include('public.partials.header')
  @php
    use Illuminate\Support\Str;
  @endphp
  <main class="enc-container">
    <section class="fav-head">
      <span class="fav-eyebrow">My Artists</span>
      <h1>나의 아티스트</h1>
      <p>찜한 아티스트를 모아보고, 필요할 때 바로 섭외 요청을 진행하세요.</p>
    </section>

    @if($artists->count())
      <div class="artist-grid">
        @foreach($artists as $artist)
          @php
            $img = $artist->image_url ?? ($artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image ?? null));
            $intro = $artist->bio ?? data_get($artist->meta ?? [], 'intro') ?? $artist->notes ?? '';
            $feeMin = $artist->fee_min ?? $artist->min_fee ?? null;
            $feeMax = $artist->fee_max ?? $artist->max_fee ?? null;
            $disciplineName = $artist->discipline->name ?? null;
          @endphp
          <article class="artist-card">
            <a class="artist-media" href="{{ route('artist.show', ['artist' => $artist->id]) }}">
              @if($img)
                <img src="{{ $img }}" alt="{{ $artist->name }} 이미지" loading="lazy" decoding="async" referrerpolicy="no-referrer">
              @else
                <span style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:12px;">이미지 준비중</span>
              @endif
            </a>
            <div class="artist-body">
              <h3><a href="{{ route('artist.show', ['artist' => $artist->id]) }}">{{ $artist->name ?? $artist->stage_name }}</a></h3>
              @if($disciplineName)
                <span class="artist-pill">{{ $disciplineName }}</span>
              @endif
              <p class="artist-desc">{{ Str::limit(strip_tags($intro), 90) }}</p>
              <div class="artist-meta">
                @if($artist->home_city)
                  <span>지역: {{ $artist->home_city }}</span>
                @endif
                @if($feeMin || $feeMax)
                  <span>가격: {{ $feeMin ? number_format($feeMin) : '?' }} ~ {{ $feeMax ? number_format($feeMax) : '?' }}원</span>
                @endif
              </div>
              <div class="artist-actions">
                <form method="post" action="{{ route('favorites.destroy', $artist) }}">
                  @csrf
                  @method('delete')
                  <button class="btn fav" type="submit">찜 해제</button>
                </form>
                <a class="btn ghost" href="{{ route('artist.show', ['artist' => $artist->id]) }}">상세 보기</a>
                <a class="btn" href="{{ route('direct.request.create', ['requested' => $artist->name ?? $artist->stage_name]) }}">의뢰하기</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      @if(method_exists($artists, 'links'))
        <div style="margin-top:18px;">{{ $artists->links() }}</div>
      @endif
    @else
      <div class="empty-state">아직 찜한 아티스트가 없습니다. 아티스트 탐색에서 마음에 드는 팀을 저장해 보세요.</div>
    @endif
  </main>
  @include('public.partials.footer')
</body>
</html>
