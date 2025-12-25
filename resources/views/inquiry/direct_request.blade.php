<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>특정 아티스트 직접 요청</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
  <style>
    :root { --bg: radial-gradient(1200px 520px at 12% -8%, rgba(243, 198, 82, 0.18), rgba(10, 10, 10, 0) 60%), radial-gradient(900px 480px at 92% 2%, rgba(255, 255, 255, 0.05), rgba(10, 10, 10, 0) 55%), #0a0a0a; --fg:#f7f4ee; --muted:#b4b0a8; --border:#2a2a2a; --card:#121212; --card-alt:#191919; --accent:#f3c652; --accent-hover:#e6b940; --danger:#8d1f2d; --font-sans:"Pretendard","Apple SD Gothic Neo","Malgun Gothic",sans-serif; }
    [data-theme="light"] { --bg: radial-gradient(980px 360px at 10% -6%, rgba(243, 198, 82, 0.14), rgba(255, 255, 255, 0) 60%), linear-gradient(180deg, #ffffff 0%, #f7f3ea 100%); --fg:#1c1b19; --muted:#6b655c; --border:#e3ddd2; --card:#ffffff; --card-alt:#f7f3ea; --accent:#f3c652; --accent-hover:#e6b940; --danger:#8d1f2d; }
    body { margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .page { max-width: 820px; margin: 0 auto; padding: 24px 20px 24px; }
    h1 { margin: 12px 0; font-size: 22px; }
    fieldset { border:1px solid var(--border); border-radius:12px; padding:16px; margin:12px 0; background:var(--card); }
    legend { color:var(--muted); padding:0 8px; }
    label { display:block; margin:8px 0 4px; font-weight:600; }
    input, select, textarea { width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; background:var(--card); color:var(--fg); }
    textarea { border:1px solid var(--border); background:var(--card); border-radius:10px; padding:12px; }
    input, select { height:44px; }
    .row { display:flex; gap:12px; flex-wrap:wrap; }
    .half { flex:1 1 320px; }
    .btn { display:inline-block; padding:10px 14px; border-radius:10px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); font-weight:700; cursor:pointer; text-decoration:none; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; border-color:var(--border); color:var(--fg); }
    input[type="checkbox"]{ width:16px; height:16px; accent-color: var(--accent); }
    .muted { color:var(--muted); font-size:13px; }
  </style>
</head>
<body>
  @include('public.partials.header', ['hideMemberNav' => true])
  <main class="page">
    <h1>특정 아티스트 직접 요청</h1>

    @if ($errors->any())
      <div style="border:1px solid var(--danger); background:rgba(141,31,45,.15); padding:10px; border-radius:10px; margin:12px 0;">
        <strong>입력값을 확인해 주세요.</strong>
        <ul style="margin:6px 0 0 18px;">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('direct.request.store') }}" enctype="multipart/form-data">
      @csrf

      <fieldset>
        <legend>단체 정보</legend>
        <div class="row">
          <div class="half">
            <label>구분</label>
            <select id="org_type" name="organization_type" required>
              <option value="">(선택)</option>
              <option value="business" @selected(old('organization_type')==='business')>사업자(법인/개인)</option>
              <option value="public" @selected(old('organization_type')==='public')>공공기관/공기업</option>
              <option value="school" @selected(old('organization_type')==='school')>학교/교육기관</option>
              <option value="nonprofit" @selected(old('organization_type')==='nonprofit')>비영리단체</option>
              <option value="other" @selected(old('organization_type')==='other')>기타 단체</option>
            </select>
            <div class="muted">개인 문의는 본 채널에서 받지 않습니다.</div>
          </div>
          <div class="half">
            <label>단체명/회사명</label>
            <input name="org_name" value="{{ old('org_name') }}" required>
          </div>
        </div>
        <div id="biz_row" class="row" style="margin-top:8px; display:none;">
          <div class="half">
            <label>사업자등록번호</label>
            <input id="biz_reg_no" name="biz_reg_no" value="{{ old('biz_reg_no') }}" placeholder="예: 123-45-67890" inputmode="numeric" maxlength="12">
          </div>
          <div class="half">
            <label>사업자등록증 사본(PDF/JPG/PNG, 10MB 이하)</label>
            <input type="file" name="biz_cert" accept="application/pdf,image/*">
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>연락처</legend>
        <div class="row">
          <div class="half">
            <label>성함</label>
            <input name="contact_name" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required>
          </div>
          <div class="half">
            <label>이메일</label>
            <input name="contact_email" type="email" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required>
          </div>
          <div class="half">
            <label>전화번호(선택)</label>
            <input name="contact_phone" value="{{ old('contact_phone') }}">
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>요청 내용</legend>
        <div class="row">
          <div class="half">
            <label>원하는 아티스트</label>
            <input name="requested_artist_name" value="{{ old('requested_artist_name', $requested ?? '') }}" required>
          </div>
          <div class="half">
            <label>행사 일정(선택)</label>
            <div class="row">
              <div class="half"><input name="event_start" type="date" value="{{ old('event_start') }}"></div>
              <div class="half"><input name="event_end" type="date" value="{{ old('event_end') }}"></div>
            </div>
          </div>
        </div>
        <label>추가 메모(선택)</label>
        <textarea name="notes" rows="8" style="width:100%;">{{ old('notes') }}</textarea>
        <label style="display:flex; gap:8px; align-items:center; margin-top:8px;">
          <input type="checkbox" name="intent_confirmed" value="1" required>
          <span class="muted">본 문의는 단체/법인/공공기관 소속이며, 실제 섭외 의사가 있습니다.</span>
        </label>
      </fieldset>

      <div style="margin-top:12px; display:flex; gap:8px;">
        <button class="btn">요청 보내기</button>
        <a class="btn ghost" href="{{ route('home') }}">홈으로</a>
      </div>
    </form>
  </main>
  @include('public.partials.footer')
  <script>
    (function(){
      const typeSel = document.getElementById('org_type');
      const bizRow = document.getElementById('biz_row');
      const bizInput = document.getElementById('biz_reg_no');
      function apply(){
        const show = typeSel && typeSel.value === 'business';
        if (bizRow) bizRow.style.display = show ? '' : 'none';
        if (bizInput) bizInput.required = !!show;
      }
      typeSel?.addEventListener('change', apply);
      apply();
    })();

    // 사업자등록번호 마스킹: 000-00-00000
    (function(){
      const el = document.getElementById('biz_reg_no');
      if (!el) return;
      function mask(v){
        const d = (v||'').replace(/\D/g,'').slice(0,10);
        if (d.length <= 3) return d;
        if (d.length <= 5) return d.slice(0,3) + '-' + d.slice(3);
        return d.slice(0,3) + '-' + d.slice(3,5) + '-' + d.slice(5);
      }
      function onInput(){
        const cur = el.selectionStart;
        const before = el.value;
        el.value = mask(before);
      }
      el.addEventListener('input', onInput);
      el.addEventListener('blur', onInput);
      onInput();
    })();
  </script>
</body>
</html>
