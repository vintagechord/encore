<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark light">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <title>{{ config('app.name', 'Encore') }} | Auth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      /* Integrate with Encore public theme */
      :root { color-scheme: dark; }
      body { margin:0; font-family: var(--font-sans, "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif); background: var(--bg); color: var(--fg); }
      .auth-wrap { min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; }
      .auth-card { width:100%; max-width: 480px; background: var(--card); border:1px solid var(--border); border-radius:14px; padding:18px 18px 20px; box-shadow: inset 0 1px 0 rgba(255,255,255,.02); }
      .auth-head { text-align:center; margin: 18px 0 8px; }
      .muted { color: var(--muted); }
      a { color: inherit; text-decoration: none; }
      a:hover { color: var(--accent-hover); text-decoration: underline; }
      .auth-link { color: var(--accent); font-weight: 600; }
      .auth-link:hover { color: var(--accent-hover); }
      .auth-card input,
      .auth-card select,
      .auth-card textarea { background: var(--card-alt) !important; color: var(--fg) !important; border-color: var(--border) !important; }
      .auth-card label { color: var(--fg); }
      .auth-card button[type="submit"],
      .auth-card .primary { background: var(--btn) !important; border-color: var(--btn) !important; color: var(--btn-text) !important; }
      .auth-card button[type="submit"]:hover,
      .auth-card .primary:hover { background: var(--btn-hover) !important; }
      .auth-card input:focus, .auth-card select:focus, .auth-card textarea:focus {
        outline: none;
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 2px rgba(243,198,82,.25);
      }
      .auth-card input[type="checkbox"] { accent-color: var(--accent); }
      .auth-card .text-indigo-300, .auth-card .text-indigo-200 { color: var(--accent) !important; }
      .auth-card .focus\\:ring-indigo-500 { --tw-ring-color: var(--accent) !important; }
      .auth-foot { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:16px; }
      .auth-links { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
      @media (max-width: 640px) {
        .auth-foot { flex-direction: column; align-items: stretch; }
        .auth-links { flex-direction: column; align-items: flex-start; gap:6px; }
        .auth-foot .primary { width:100%; justify-content:center; }
      }
    </style>
  </head>
  <body>
    @include('public.partials.header')
    <main class="auth-wrap">
      <div class="auth-card">
        {{ $slot }}
      </div>
    </main>
    @include('public.partials.footer')
  </body>
</html>
