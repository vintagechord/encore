<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>공지사항 | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system,system-ui,Segoe UI,Roboto,Helvetica,Arial,Apple SD Gothic Neo,Malgun Gothic,sans-serif; }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:12px; padding:12px; }
    .list{ list-style:none; padding:0; margin:0; display:grid; gap:10px; }
    .item{ display:flex; justify-content:space-between; align-items:center; gap:14px; }
    .muted{ color:var(--muted); }
    .pager{ display:flex; justify-content:center; margin-top:12px; }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <h1 style="margin:0 0 12px">공지사항</h1>
    <ul class="list">
      @forelse($notices as $n)
        <li class="card item">
          <a href="{{ route('notices.show', $n) }}">{{ $n->title }}</a>
          <span class="muted">{{ optional($n->published_at ?? $n->created_at)->format('Y-m-d') }}</span>
        </li>
      @empty
        <li class="card muted">공지사항이 없습니다.</li>
      @endforelse
    </ul>
    <div class="pager">{{ $notices->links('pagination::encore') }}</div>
  </main>
  @include('public.partials.footer')
</body>
</html>

