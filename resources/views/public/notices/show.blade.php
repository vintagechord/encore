<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>{{ $notice->title }} | 공지사항</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:var(--font-sans); }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    .card{ background:var(--card); border:1px solid var(--border); border-radius:12px; padding:16px; }
    .muted{ color:var(--muted); }
    .content{ line-height:1.7 }
  </style>
</head>
<body>
  @include('public.partials.header')
  <main class="enc-container">
    <a class="muted" href="{{ route('notices.index') }}">← 목록으로</a>
    <article class="card" style="margin-top:10px">
      <h1 style="margin:0 0 8px">{{ $notice->title }}</h1>
      <div class="muted" style="margin-bottom:10px">{{ optional($notice->published_at ?? $notice->created_at)->format('Y-m-d H:i') }}</div>
      <div class="content">{!! nl2br(e($notice->body)) !!}</div>
    </article>
  </main>
  @include('public.partials.footer')
</body>
</html>
