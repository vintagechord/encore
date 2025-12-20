<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>@yield('title','관리자')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
        radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
        #0b090a;
      --fg:#f7f1e9;
      --muted:#b6a89a;
      --card:#151012;
      --card-alt:#1b1316;
      --border:#2a1c22;
      --accent:#f3c652;
      --accent-hover:#f0b840;
      --chip: rgba(141,31,45,.2);
      --chip-border: rgba(141,31,45,.4);
      --font-sans: "Noto Sans KR","Apple SD Gothic Neo","Malgun Gothic",sans-serif;
      --font-display: "Noto Serif KR","Apple SD Gothic Neo","Malgun Gothic",serif;
    }
    [data-theme="light"]{
      --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%),
        linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%);
      --fg:#2b1b1b;
      --muted:#6b5b53;
      --card:#fffdf8;
      --card-alt:#f6ecdd;
      --border:#e6d4c0;
      --accent:#e3b648;
      --accent-hover:#d5a63b;
      --chip: rgba(122,30,46,.12);
      --chip-border: rgba(122,30,46,.28);
    }
    body{ margin:0; background:var(--bg); color:var(--fg); font-family: var(--font-sans); }
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
