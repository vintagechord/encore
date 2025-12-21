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
    <title>{{ config('app.name', 'Encore') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      .enc-container { max-width:1120px; margin:0 auto; padding:0 20px; }
      .pg-head { border-bottom:1px solid var(--border); background: transparent; }
      .page-wrap { min-height: calc(100vh - 180px); }
      body { margin:0; background: var(--bg); color: var(--fg); font-family: var(--font-sans); }
    </style>
  </head>
  <body>
    @include('public.partials.header')
    @isset($header)
      <header class="pg-head">
        <div class="enc-container" style="padding:14px 20px;">
          {{ $header }}
        </div>
      </header>
    @endisset
    <main class="page-wrap">
      {{ $slot }}
    </main>
    @include('public.partials.footer')
  </body>
</html>
