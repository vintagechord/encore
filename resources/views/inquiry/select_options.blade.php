<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>추천안 선택 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    :root { --ring:#8d1f2d; --ring-soft: rgba(141,31,45,.25); }
    body { margin:0; background: var(--bg); color: var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:24px 20px; }
    .head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
    .head h1 { margin:0; font-size:22px; letter-spacing:-0.01em; }
    .muted{ color: var(--muted); }
    /* slider layout */
    .carousel { position:relative; overflow:hidden; }
    .track { display:flex; gap:14px; overflow-x:auto; scroll-snap-type:x mandatory; padding-bottom:8px; }
    .track::-webkit-scrollbar{ height:8px; }
    .track > article { scroll-snap-align:center; }
    .opt { position:relative; background: var(--card); border:1px solid var(--border); border-radius: 16px; padding: 16px; display:flex; flex-direction:column; gap:10px; align-items:center; text-align:center; box-shadow: inset 0 1px 0 rgba(255,255,255,.02); transform: scale(.92); transition: transform .25s ease, border-color .2s ease; min-width: 280px; }
    .opt.active { transform: scale(1.04); border-color: var(--btn); }
    .thumb-wrap { position:relative; width: 320px; max-width: 86vw; aspect-ratio: 4/3; border-radius: 14px; overflow:hidden; border:1px solid var(--border); background: var(--card-alt); display:grid; place-items:center; }
    .collage { display:grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); width:100%; height:100%; }
    .collage img { width:100%; height:100%; object-fit:cover; display:block; }
    /* Remove halo glow to avoid rare white block artifacts on some browsers */
    .halo { display:none; }
    .label { display:inline-flex; align-items:center; gap:8px; padding:4px 10px; border-radius:999px; border:1px solid var(--chip-border); background: var(--chip); font-weight:700; }
    .title { margin:0; font-size:18px; }
    .price { font-weight:700; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--btn); background: var(--btn); color:var(--btn-text); cursor:pointer; text-decoration:none; font-weight:600; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost { background: transparent; color: var(--fg); border-color: var(--border); }
    .opt:hover { border-color: var(--chip-border); }
    .opt:hover .thumb-wrap { box-shadow: 0 0 0 2px var(--ring-soft) inset; }
    .nav { display:flex; justify-content:center; gap:10px; margin-top:8px; }
    .nav button { width:40px; height:40px; border-radius:999px; border:1px solid var(--border); background:var(--card); color:var(--fg); }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    @php $opts = $options ?? []; @endphp

    <div class="head">
      <h1>추천안 선택</h1>
      @php
        // 현재 옵션에 포함된 아티스트를 다음 생성에서 제외해 다양성 향상
        $exIds = [];
        foreach (($options ?? []) as $oo) {
          foreach (($oo['artists'] ?? []) as $ar) {
            if (!empty($ar['artist_id'])) $exIds[] = (int)$ar['artist_id'];
          }
        }
        $exIds = array_values(array_unique(array_filter($exIds)));
        $regenUrl = route('inquiry.select_options', [
          'intake' => $intake->id,
          'seed'   => (string) \Illuminate\Support\Str::uuid(),
          'exclude'=> implode(',', $exIds),
        ]);
      @endphp
      <a class="btn ghost" href="{{ $regenUrl }}" title="새로운 조합의 추천안을 다시 생성합니다">다른 추천안 생성</a>
    </div>

    <p class="muted" style="margin:0 0 10px">아래 3가지 옵션 중 하나를 선택해 문의를 이어가세요.</p>

    <section class="carousel" aria-label="추천 옵션">
      <div class="track" id="optTrack">
        @foreach($opts as $idx=>$o)
          @php 
            if (isset($set)) {
              $cta = !empty($set->public_token)
                ? route('share.token.option', ['token'=>$set->public_token, 'idx'=>$idx])
                : route('member.recommendations.option', ['intake'=>$intake->id, 'idx'=>$idx]);
            } else {
              $cta = route('inquiry.option.preview', ['intake'=>$intake->id, 'idx'=>$idx, 'seed'=>$seed ?? null]);
            }
            $artists = $o['artists'] ?? ($o['title']?[[ 'title'=>$o['title'], 'image'=>$o['image']??null, 'fee_min'=>null, 'fee_max'=>null ]]:[]);
            // 문의에서 입력한 예산 범위를 우선 적용(신규 플로우)
            $useInquiryBudget = !isset($set);
            $min = $useInquiryBudget ? ($intake->budget_min ?? ($o['budget_min'] ?? null)) : ($o['budget_min'] ?? null);
            $max = $useInquiryBudget ? ($intake->budget_max ?? ($o['budget_max'] ?? null)) : ($o['budget_max'] ?? null);
          @endphp
          <article class="opt" role="group" aria-labelledby="opt-{{ $idx }}-t">
            <span class="label">옵션 {{ $idx+1 }}</span>
            <figure class="thumb-wrap" aria-hidden="true">
              <div class="collage">
                @foreach(array_slice($artists,0,6) as $a)
                  @if(!empty($a['image']))
                    <img src="{{ $a['image'] }}" alt="{{ $a['title'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                  @else
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='90'%3E%3Crect width='100%25' height='100%25' fill='%231a253d'/%3E%3C/svg%3E" alt="" />
                  @endif
                @endforeach
              </div>
              <div class="halo"></div>
            </figure>
            <h3 id="opt-{{ $idx }}-t" class="title">@if(!empty($artists)) {{ implode(' · ', array_map(fn($x)=>$x['title'], array_slice($artists,0,3))) }} @else 구성 준비중 @endif</h3>
            <div class="price">@if($min||$max) 총예산 {{ $min?number_format($min):'?' }} ~ {{ $max?number_format($max):'?' }} 원 @else 견적 산정중 @endif</div>
            <a class="btn" href="{{ $cta }}">이 옵션 보기</a>
          </article>
        @endforeach
      </div>
      <div class="nav">
        <button type="button" id="prevBtn" aria-label="이전">‹</button>
        <button type="button" id="nextBtn" aria-label="다음">›</button>
      </div>
    </section>
  </main>
  @include('public.partials.footer')
  <script>
    (function(){
      const track = document.getElementById('optTrack');
      const cards = Array.from(track.querySelectorAll('.opt'));
      const prev = document.getElementById('prevBtn');
      const next = document.getElementById('nextBtn');

      // Center given index in view
      function centerCard(i, smooth=true){
        const c = cards[i];
        if (!c) return;
        const left = c.offsetLeft - (track.clientWidth - c.clientWidth)/2;
        const max = track.scrollWidth - track.clientWidth;
        const target = Math.max(0, Math.min(left, max));
        track.scrollTo({ left: target, behavior: smooth ? 'smooth' : 'auto' });
        setActive(i);
        current = i;
      }

      function setActive(i){
        cards.forEach(el => el.classList.remove('active'));
        cards[i]?.classList.add('active');
      }

      // Find nearest card to center
      function nearestIndex(){
        const center = track.scrollLeft + track.clientWidth/2;
        let best = 0, bestDist = Infinity;
        cards.forEach((c, i) => {
          const cx = c.offsetLeft + c.clientWidth/2;
          const d = Math.abs(cx - center);
          if (d < bestDist) { bestDist = d; best = i; }
        });
        return best;
      }

      let current = Math.floor(cards.length/2);
      let suppressUntil = 0; // avoid race with scroll handler during programmatic centering
      centerCard(current, false);

      // Buttons rotate through options in a loop
      prev.addEventListener('click', () => {
        current = (nearestIndex() - 1 + cards.length) % cards.length;
        suppressUntil = Date.now() + 500;
        centerCard(current);
      });
      next.addEventListener('click', () => {
        current = (nearestIndex() + 1) % cards.length;
        suppressUntil = Date.now() + 500;
        centerCard(current);
      });

      // Keep active state in sync when user scrolls manually
      let rafId = null;
      track.addEventListener('scroll', () => {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = requestAnimationFrame(() => {
          if (Date.now() < suppressUntil) return; // keep selection stable while animating
          setActive(nearestIndex());
        });
      }, { passive: true });

      // Card click only activates/centers; navigation happens via the button
      cards.forEach((card, i) => {
        const btn = card.querySelector('a.btn');
        card.style.cursor='pointer';
        card.addEventListener('click', function(e){
          if (e.target.closest('a')) return; // keep button behaviour
          e.preventDefault();
          suppressUntil = Date.now() + 500;
          centerCard(i);
        });
        // Keyboard: Enter/Space on card focuses the button
        card.tabIndex = 0;
        card.addEventListener('keydown', (ev) => {
          if (ev.key === 'Enter' || ev.key === ' ') {
            ev.preventDefault();
            btn?.focus();
          }
        });
      });
    })();
  </script>
</body>
</html>
