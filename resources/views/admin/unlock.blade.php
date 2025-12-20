<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>관리자 잠금 해제</title>
  <style>
    :root{ --bg:#050912; --fg:#e6edff; --muted:#97a6c9; --card:#0f1729; --border:#1f2b41; --accent:#6366f1; --accent-hover:#818cf8; }
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system,system-ui,Segoe UI,Roboto,Helvetica,Arial,Apple SD Gothic Neo,Malgun Gothic,sans-serif; }
    .wrap{ min-height:100vh; display:grid; place-items:center; padding:20px; }
    .panel{ width:min(420px, 92vw); background:var(--card); border:1px solid var(--border); border-radius:14px; padding:18px; box-shadow:0 16px 40px rgba(4,8,18,.45); }
    h1{ margin:0 0 10px; font-size:20px; }
    .muted{ color:var(--muted); }
    label{ display:block; margin:0 0 8px; }
    input[type="password"]{ width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--border); background:#0d1626; color:var(--fg); }
    input[type="password"]:focus{ outline:none; border-color:var(--accent); box-shadow:0 0 0 2px rgba(99,102,241,.25); }
    .row{ display:flex; gap:8px; align-items:center; justify-content:flex-end; }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 12px; border-radius:10px; border:1px solid var(--accent); background:var(--accent); color:#fff; text-decoration:none; cursor:pointer; font-weight:700; }
    .btn:hover{ background:var(--accent-hover); border-color:var(--accent-hover); }
    .err{ color:#fca5a5; margin:8px 0 0; }
  </style>
</head>
<body>
  <div class="wrap">
    <form method="post" class="panel" action="{{ route('admin.unlock') }}">
      @csrf
      <input type="hidden" name="return" value="{{ $return ?? '' }}">
      <h1>관리자 잠금 해제</h1>
      <p class="muted" style="margin:0 0 12px">관리자 비밀번호를 입력하세요.</p>
      <label>
        비밀번호
        <input type="password" name="password" required autofocus autocomplete="off">
      </label>
      @if(!empty($error))
        <div class="err">{{ $error }}</div>
      @endif
      <div class="row" style="margin-top:12px">
        <button class="btn" type="submit">입장</button>
      </div>
    </form>
  </div>
</body>
</html>

