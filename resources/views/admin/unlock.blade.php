<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>관리자 잠금 해제</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
  <style>
    :root{ --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%), radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%), #0b090a; --fg:#f7f1e9; --muted:#b6a89a; --card:#151012; --border:#2a1c22; --accent:#f3c652; --accent-hover:#f0b840; --font-sans:"Noto Sans KR","Apple SD Gothic Neo","Malgun Gothic",sans-serif; }
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .wrap{ min-height:100vh; display:grid; place-items:center; padding:20px; }
    .panel{ width:min(420px, 92vw); background:var(--card); border:1px solid var(--border); border-radius:14px; padding:18px; box-shadow:0 16px 40px rgba(4,8,18,.45); }
    h1{ margin:0 0 10px; font-size:20px; }
    .muted{ color:var(--muted); }
    label{ display:block; margin:0 0 8px; }
    input[type="password"]{ width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--border); background:#1a1316; color:var(--fg); }
    input[type="password"]:focus{ outline:none; border-color:var(--accent); box-shadow:0 0 0 2px rgba(243,198,82,.25); }
    .row{ display:flex; gap:8px; align-items:center; justify-content:flex-end; }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 12px; border-radius:10px; border:1px solid var(--accent); background:var(--accent); color:#1b130f; text-decoration:none; cursor:pointer; font-weight:700; }
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
