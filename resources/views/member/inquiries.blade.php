<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>의뢰내역 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    /* Use shared theme variables from public/partials/header */
    body { margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:1120px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .muted{ color:var(--muted); }
    .row{ display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
    a{ color:inherit; text-decoration:none; }
    a:hover{ color:var(--accent-hover); text-decoration:underline; }
    .btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; height:36px; padding:0 12px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; font-weight:600; line-height:1; box-sizing:border-box; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; color:var(--fg); border-color:var(--border); }
    /* 동일 배치 내 상태 버튼은 고정 폭으로 균일화 */
    .status-btn{ min-width: 108px; justify-content:center; text-align:center; }
    /* pagination */
    .pager{ display:flex; justify-content:center; margin-top:12px; }
    .pager nav{ display:inline-flex; gap:6px; align-items:center; background:var(--card); border:1px solid var(--border); border-radius:999px; padding:6px; }
    .pager nav a, .pager nav span{ display:inline-flex; min-width:34px; height:34px; padding:0 10px; align-items:center; justify-content:center; border-radius:999px; border:1px solid transparent; color:var(--fg); text-decoration:none; }
    .pager nav a:hover{ border-color:var(--chip-border); background:var(--card-alt); }
    .pager nav span[aria-current="page"], .pager nav .active{ background:var(--btn); color:var(--btn-text); border-color:var(--btn); }
    .pager nav .disabled{ opacity:.45; cursor:not-allowed; }
    @media (max-width: 640px){ .enc-container{ padding:16px 14px; } .btn{ height:34px; padding:0 10px; border-radius:10px; line-height:1; } }
    .list{ list-style:none; padding:0; margin:0; display:grid; gap:10px; }
    .enc-modal{ position:fixed; inset:0; background:rgba(0,0,0,.55); display:none; align-items:center; justify-content:center; z-index:60; }
    .enc-modal[aria-hidden="false"]{ display:flex; }
    .enc-modal .win{ width:min(420px, 90vw); background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; box-shadow:0 18px 40px rgba(0,0,0,.4); }
    .enc-modal .win h2{ margin:0 0 6px; font-size:18px; }
    .enc-modal .actions{ display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }
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
    @php $t = $type ?? request()->query('type'); @endphp
    <nav class="subtabs" aria-label="의뢰내역 세부 분류">
      <a class="tab {{ $t==='instant' ? 'active' : '' }}" href="{{ route('member.inquiries',['type'=>'instant']) }}">1초 Set</a>
      <a class="tab {{ $t==='one_day' ? 'active' : '' }}" href="{{ route('member.inquiries',['type'=>'one_day']) }}">1일 Set</a>
      <a class="tab {{ $t==='direct' ? 'active' : '' }}" href="{{ route('member.inquiries',['type'=>'direct']) }}">아티스트 문의</a>
    </nav>

    @php $t = $type ?? request()->query('type'); @endphp
    @if($t !== 'direct')
    <section class="card" style="margin-bottom:12px">
      <h2 style="margin:0 0 8px">옵션 의뢰</h2>
      @if(count($optionInquiries) === 0)
        <p class="muted" style="margin:0">옵션 문의가 없습니다.</p>
      @else
        <ul class="list">
          @foreach($optionInquiries as $inq)
            @php 
                $set = $inq->latestSet; 
                $status = $inq->status; 
                $notes = json_decode((string)$inq->notes, true); 
                $amount = (int)($notes['budget_max'] ?? $inq->budget_max ?? 0);
                $rtype = $notes['request_type'] ?? ($inq->category==='direct'?'direct':'instant');
                $label = $rtype==='one_day' ? '1일 Set 추천안' : ($rtype==='direct' ? '아티스트 맞춤형' : '1초 Set 추천안');
                $isOrder = (isset($notes['type']) && $notes['type']==='option_order');
                // 진행상태 텍스트 매핑
                $statusText = match($status){
                    'new' => '접수완료',
                    'processing' => '견적확인',
                    'recommended' => '결제하기',
                    'closed' => '결제완료',
                    'booked' => '섭외완료',
                    'completed' => '행사완료',
                    default => '진행중',
                };
                if ($isOrder && $status === 'new') { $statusText = '접수완료'; }
                // 옵션 보기 링크 결정(옵션 의뢰에만)
                $optUrl = null;
                if ($isOrder) {
                    $optIdx = (int)($notes['option_index'] ?? 0);
                    if (!empty($notes['public_token'])) {
                        $optUrl = route('share.token.option', ['token'=>$notes['public_token'], 'idx'=>$optIdx]);
                    } elseif (!empty($notes['set_id'])) {
                        $setRef = \App\Models\RecommendationSet::find($notes['set_id']);
                        if ($setRef) { $optUrl = route('member.recommendations.option', ['intake'=>$setRef->intake_request_id, 'idx'=>$optIdx]); }
                    }
                }
            @endphp
            <li class="card">
              <div class="row">
                <div style="flex:1 1 auto; min-width:260px;">
                  @php
                    $titleUrl = ($rtype==='one_day') ? route('inquiry.thanks') : (
                      $set
                        ? (!empty($set->public_token)
                            ? route('share.token', ['token'=>$set->public_token])
                            : route('member.recommendations', ['intake'=>$inq->id]))
                        : route('inquiry.select_options', ['intake'=>$inq->id])
                    );
                  @endphp
                  <a href="{{ $titleUrl }}" style="text-decoration:none;color:inherit">
                    <strong>#{{ $inq->id }}</strong> <span class="muted">{{ $label }}</span>
                    <span class="muted">{{ optional($inq->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
                  </a>
                  @if($isOrder && $optUrl)
                    <div style="margin-top:6px;">
                      <a class="btn" href="{{ $optUrl }}">옵션 보기</a>
                    </div>
                  @endif
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap; justify-content:flex-end; flex: 0 0 auto;">
                  @php 
                    $payLink = null;
                    try {
                      if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                        $p = \App\Models\Payment::where('user_id', auth()->id())
                            ->where('meta->intake_id', $inq->id)
                            ->latest('id')->first();
                        if ($p) $payLink = route('payment.show', ['payment'=>$p->id]);
                      }
                    } catch (\Throwable $e) {}
                  @endphp
                  {{-- 의뢰내역에서는 추천안 보기를 제공하지 않습니다 (추천셋 페이지에서 확인) --}}

                  @if($status === 'closed')
                    <a class="btn status-btn" href="{{ $payLink ?: route('member.payments') }}">결제 완료</a>
                  @elseif($status === 'recommended')
                    <a class="btn status-btn" href="{{ route('pay.create', ['intake'=>$inq->id, 'amount'=>$amount]) }}">결제 하기</a>
                  @elseif(in_array($status, ['booked','completed']))
                    <span class="btn ghost status-btn" aria-label="상태">{{ $statusText }}</span>
                  @endif
                  @if($status === 'new')
                    <button type="button" class="btn ghost status-btn js-status-info" data-message="아티스트 섭외 관련 조율중입니다">{{ $statusText }}</button>
                  @elseif($status === 'processing')
                    <span class="btn ghost status-btn" aria-label="상태">{{ $statusText }}</span>
                  @endif
                </div>
              </div>
              <div class="muted" style="margin-top:6px">총예산 {{ $inq->budget_min?number_format($inq->budget_min):'?' }} ~ {{ $inq->budget_max?number_format($inq->budget_max):'?' }} 원</div>
            </li>
          @endforeach
        </ul>
        <div class="pager">{{ $optionInquiries->links('pagination::encore') }}</div>
      @endif
    </section>
    @endif

    @if($t === 'direct')
      <section class="card">
        <h2 style="margin:0 0 8px">개별 아티스트 문의</h2>
        @if(count($directInquiries) === 0)
          <p class="muted" style="margin:0">개별 아티스트 문의가 없습니다.</p>
        @else
          <ul class="list">
            @foreach($directInquiries as $inq)
              <li class="card">
                <div class="row">
                  <div style="flex:1 1 auto; min-width:260px;">
                    <strong>#{{ $inq->id }}</strong>
                    <span class="muted">{{ optional($inq->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
                    <div class="muted" style="margin-top:6px">요청 아티스트: {{ $inq->requested_artist_name ?? '미상' }}</div>
                  </div>
                  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap; justify-content:flex-end; flex: 0 0 auto;">
                    @php 
                      $status = $inq->status;
                      $statusText = match($status){
                        'new' => '접수완료',
                        'processing' => '견적확인',
                        'recommended' => '결제하기',
                        'closed' => '결제완료',
                        'booked' => '섭외완료',
                        'completed' => '행사완료',
                        default => '진행중',
                      };
                      $amount = (int)($inq->budget_max ?? 0);
                      $payLink = null;
                      try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                          $p = \App\Models\Payment::where('user_id', auth()->id())
                              ->where('meta->intake_id', $inq->id)
                              ->latest('id')->first();
                          if ($p) $payLink = route('payment.show', ['payment'=>$p->id]);
                        }
                      } catch (\Throwable $e) {}
                    @endphp
                    @if($status === 'closed')
                      <a class="btn status-btn" href="{{ $payLink ?: route('member.payments') }}">결제 완료</a>
                    @elseif($status === 'recommended')
                      <a class="btn status-btn" href="{{ route('pay.create', ['intake'=>$inq->id, 'amount'=>$amount]) }}">결제 하기</a>
                    @else
                      @if($status === 'new')
                        <button type="button" class="btn ghost status-btn js-status-info" data-message="아티스트 섭외 관련 조율중입니다">{{ $statusText }}</button>
                      @else
                        <span class="btn ghost status-btn" aria-label="상태">{{ $statusText }}</span>
                      @endif
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          <div class="pager">{{ $directInquiries->links('pagination::encore') }}</div>
        @endif
      </section>
    @endif
  </main>
  <div class="enc-modal" id="statusModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="진행 상태 안내">
    <div class="win">
      <h2>진행 상태 안내</h2>
      <p class="muted" id="statusModalMsg" style="margin:0">상태 안내 메시지</p>
      <div class="actions">
        <button type="button" class="btn ghost" id="statusModalClose">닫기</button>
      </div>
    </div>
  </div>
  @include('public.partials.footer')
  <script>
    // 접수완료 상태 안내: 클릭 시 모달
    (function(){
      const modal = document.getElementById('statusModal');
      const msgEl = document.getElementById('statusModalMsg');
      const closeBtn = document.getElementById('statusModalClose');
      const open = (msg) => {
        if (!modal || !msgEl) return;
        msgEl.textContent = msg;
        modal.setAttribute('aria-hidden', 'false');
      };
      const close = () => {
        if (!modal) return;
        modal.setAttribute('aria-hidden', 'true');
      };
      document.addEventListener('click', function(e){
        const btn = e.target.closest('.js-status-info');
        if (!btn) return;
        const msg = btn.getAttribute('data-message') || '아티스트 섭외 관련 조율중입니다';
        open(msg);
      });
      closeBtn?.addEventListener('click', close);
      modal?.addEventListener('click', (e) => {
        if (e.target === modal) close();
      });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
      });
    })();
  </script>
  <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light')?'light':'dark';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
</body>
</html>
