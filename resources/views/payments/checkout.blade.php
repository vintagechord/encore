<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>결제하기 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,Apple SD Gothic Neo,Malgun Gothic,sans-serif; }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .row{ display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
    .field{ display:flex; gap:10px; align-items:center; }
    .muted{ color:var(--muted); }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--accent); background:var(--accent); color:#fff; text-decoration:none; }
    input[type="text"], input[type="number"]{ padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--card-alt); color:var(--fg); }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <h1 style="margin:0 0 12px">결제하기</h1>
    <form id="payForm" class="card" method="post" action="{{ route('pay.store', ['intake'=>$intake->id]) }}">
      @csrf
      <input type="hidden" name="amount" value="{{ $amount }}">
      <div class="row" style="justify-content:space-between">
        <div>
          <div class="muted">주문 대상</div>
          <div><strong>문의 #{{ $intake->id }}</strong> @if($meta['option']??false)<span class="muted">(옵션 의뢰)</span>@endif</div>
        </div>
        <div>
          <div class="muted">총 주문합계</div>
          <div style="font-weight:700; font-size:18px">{{ number_format($amount) }} 원</div>
        </div>
      </div>
      <hr style="border-color:var(--border);opacity:.6;margin:12px 0">

      <div class="field"><strong>결제방식</strong>
        <label style="display:flex;gap:6px;align-items:center"><input type="radio" name="method" value="card" checked> 신용카드</label>
        <label style="display:flex;gap:6px;align-items:center"><input type="radio" name="method" value="bank"> 무통장입금</label>
        <span class="muted">무통장: 국민 073001-04-276967 (주)빈티지하우스</span>
      </div>
      <div class="field" style="margin-top:10px">
        <label class="muted" style="min-width:80px">쿠폰번호</label>
        <input type="text" placeholder="예: ENCORE10">
        <button type="button" class="btn" onclick="alert('데모: 쿠폰 기능은 예시입니다')">사용하기</button>
      </div>

      <div class="card" style="margin-top:12px">
        <div class="row" style="justify-content:space-between">
          <div>
            <div class="muted">합계</div>
            <div>고객님의 총 주문 합계 금액입니다.</div>
          </div>
          <div style="text-align:right">
            <div class="muted">상품합계금액</div>
            <div>{{ number_format($amount) }} 원</div>
            <div class="muted" style="margin-top:6px">총 주문합계 금액 in KRW</div>
            <div style="font-weight:800; color:#ef4444; font-size:18px">{{ number_format($amount) }} 원</div>
          </div>
        </div>
        <div class="row" style="justify-content:flex-end; margin-top:12px">
          <a href="{{ route('member.dashboard') }}" class="btn" style="background:transparent;border-color:var(--border);color:var(--fg)">주문취소</a>
          <button id="orderBtn" class="btn" style="background:#ef4444;border-color:#ef4444">주문하기</button>
        </div>
      </div>
    </form>

    <!-- KG Mobilians Demo Modal -->
    <style>
      .kg-modal{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:50}
      .kg-modal .win{width:min(520px,92vw);border-radius:12px;border:1px solid var(--border);background:var(--card);padding:16px}
      .kg-modal .row{display:flex;gap:10px;justify-content:flex-end;margin-top:12px}
    </style>
    <div class="kg-modal" id="kgModal" role="dialog" aria-modal="true" aria-label="KG Mobilians 결제">
      <div class="win">
        <h2 style="margin:0 0 6px">KG Mobilians (데모)</h2>
        <p class="muted" style="margin:0">실결제 연동 전 데모 모듈입니다. “결제 진행”을 누르면 결제가 완료로 처리됩니다.</p>
        <div class="row">
          <button id="kgClose" class="btn" style="background:transparent;border-color:var(--border);color:var(--fg)">취소</button>
          <button id="kgPay" class="btn">결제 진행</button>
        </div>
      </div>
    </div>
  </main>
  @include('public.partials.footer')
  <script>
    (function(){
      const form = document.getElementById('payForm');
      const btn = document.getElementById('orderBtn');
      const modal = document.getElementById('kgModal');
      const mOpen = () => { modal.style.display='flex'; };
      const mClose = () => { modal.style.display='none'; };
      document.getElementById('kgClose')?.addEventListener('click', mClose);
      document.getElementById('kgPay')?.addEventListener('click', function(){
        // proceed to server to create a paid record
        mClose();
        form.submit();
      });
      form.addEventListener('submit', function(e){
        // intercept only for card
        const method = form.querySelector('input[name="method"]:checked')?.value || 'card';
        if (method === 'card') {
          e.preventDefault();
          mOpen();
        }
      });
    })();
  </script>
</body>
</html>
