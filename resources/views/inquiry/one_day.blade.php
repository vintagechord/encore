<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>1일 Set 문의 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .row{ display:flex; gap:12px; flex-wrap:wrap; }
    label{ display:block; font-weight:600; margin:6px 0; }
    input, textarea{ width:100%; padding:10px; border-radius:10px; border:1px solid var(--border); background:var(--card-alt); color:var(--fg); }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--btn); background:var(--btn); color:var(--btn-text); text-decoration:none; cursor:pointer; font-weight:600; }
    .btn:hover{ background:var(--btn-hover); border-color:var(--btn-hover); }
    .btn.ghost{ background:transparent; border-color:var(--border); color:var(--fg); }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <h1 style="margin:0 0 8px">1일 Set 문의</h1>
    <p class="muted" style="margin:0 0 10px">요구사항을 자세히 적어 보내주세요. 관리자가 검토 후 1일 내 3가지 셋을 전달합니다.</p>

    <form method="post" action="{{ route('inquiry.one_day.store') }}" class="card">
      @csrf
      <div class="row">
        <div style="flex:1 1 260px">
          <label for="contact_name">성함</label>
          <input id="contact_name" name="contact_name" required value="{{ old('contact_name', auth()->user()->name ?? '') }}">
        </div>
        <div style="flex:1 1 260px">
          <label for="contact_email">이메일</label>
          <input id="contact_email" name="contact_email" type="email" required value="{{ old('contact_email', auth()->user()->email ?? '') }}">
        </div>
        <div style="flex:1 1 200px">
          <label for="contact_phone">전화번호(선택)</label>
          <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
        </div>
      </div>

      <div class="row">
        <div style="flex:1 1 200px">
          <label for="event_start">행사 시작일</label>
          <input id="event_start" name="event_start" type="date" value="{{ old('event_start') }}">
        </div>
        <div style="flex:1 1 200px">
          <label for="event_end">행사 종료일</label>
          <input id="event_end" name="event_end" type="date" value="{{ old('event_end') }}">
        </div>
        <div style="flex:1 1 200px">
          <label for="budget_min">최소 예산</label>
          <input id="budget_min" name="budget_min" type="number" min="0" value="{{ old('budget_min') }}">
        </div>
        <div style="flex:1 1 200px">
          <label for="budget_max">최대 예산</label>
          <input id="budget_max" name="budget_max" type="number" min="0" value="{{ old('budget_max') }}">
        </div>
      </div>

      <div>
        <label for="requirements">행사/공연 설명 및 요구사항(필수)</label>
        <textarea id="requirements" name="requirements" rows="8" required placeholder="행사 목적, 예상 관객, 원하는 분위기/장르, 제한사항 등을 자유롭게 적어주세요.">{{ old('requirements') }}</textarea>
      </div>

      <div style="margin-top:12px; display:flex; gap:8px; justify-content:flex-end">
        <a class="btn ghost" href="{{ route('home') }}">취소</a>
        <button class="btn" type="submit">접수하기</button>
      </div>
    </form>
  </main>
  @include('public.partials.footer')
</body>
</html>
