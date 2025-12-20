<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark light">
    <title>{{ config('app.name', 'Encore') }} | Auth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      /* Integrate with Encore public theme */
      :root { color-scheme: dark; }
      body { margin:0; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, Apple SD Gothic Neo, Malgun Gothic, sans-serif; background: var(--bg); color: var(--fg); }
      .auth-wrap { min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; }
      .auth-card { width:100%; max-width: 480px; background: var(--card); border:1px solid var(--border); border-radius:14px; padding:18px 18px 20px; box-shadow: inset 0 1px 0 rgba(255,255,255,.02); }
      .auth-head { text-align:center; margin: 18px 0 8px; }
      .muted { color: var(--muted); }
      a { color: inherit; text-decoration: none; }
      a:hover { color: var(--accent-hover); text-decoration: underline; }
      .auth-card input,
      .auth-card select,
      .auth-card textarea { background: var(--card-alt) !important; color: var(--fg) !important; border-color: var(--border) !important; }
      .auth-card label { color: var(--fg); }
      .auth-card button[type="submit"],
      .auth-card .primary { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
      .auth-card button[type="submit"]:hover,
      .auth-card .primary:hover { background: var(--accent-hover) !important; }
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
