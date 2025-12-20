<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>@yield('title','관리자')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{ --bg:#050912; --fg:#e6edff; --muted:#97a6c9; --card:#0f1729; --card-alt:#152033; --border:#1f2b41; --accent:#6366f1; --accent-hover:#818cf8; --chip: rgba(99,102,241,.18); --chip-border: rgba(99,102,241,.35); }
    [data-theme="light"]{ --bg:#f8fafc; --fg:#0f1729; --muted:#475569; --card:#fff; --card-alt:#f1f5f9; --border:#d7dce2; --accent:#4f46e5; --accent-hover:#4338ca; --chip: rgba(79,70,229,.10); --chip-border: rgba(79,70,229,.28); }
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system,system-ui,Segoe UI,Roboto,Helvetica,Arial,Apple SD Gothic Neo,Malgun Gothic,sans-serif; }
  </style>
  @stack('head')
</head>
<body>
  {{-- Admin pages should not show member user nav; hide it here --}}
  @include('public.partials.header', ['hideMemberNav' => true])
  <x-admin-shell>
    @yield('content')
  </x-admin-shell>
  @include('public.partials.footer')
  @stack('scripts')
  <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light')?'light':'dark';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
</body>
</html>
