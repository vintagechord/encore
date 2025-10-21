<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>@yield('title', '관리자')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --bg: #050912;
            --surface: #0f1729;
            --surface-alt: #152033;
            --border: #1f2b41;
            --border-soft: #273554;
            --text: #e5ecff;
            --muted: #96a6c6;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --danger: #ef4444;
            --success: #34d399;
        }

        body {
            font-family: -apple-system, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            margin: 24px;
            color: var(--text);
            background: var(--bg);
        }

        a {
            color: #8da2fb;
            text-decoration: none;
            transition: color .2s ease;
        }

        a:hover {
            color: #b3c0ff;
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 20px 60px rgba(4, 8, 18, 0.5);
        }

        .nav {
            margin-bottom: 16px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .muted {
            color: var(--muted);
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            background: var(--surface-alt);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .alert {
            padding: 10px 12px;
            border-radius: 6px;
            margin: 10px 0;
            border: 1px solid var(--border);
            background: var(--surface-alt);
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.12);
            border-color: rgba(52, 211, 153, 0.4);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(248, 113, 113, 0.15);
            border-color: rgba(248, 113, 113, 0.4);
            color: #fca5a5;
        }

        .btn {
            display: inline-block;
            padding: 6px 10px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease, border-color .2s ease, opacity .2s ease;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
        }

        .btn-ghost {
            background: transparent;
            border-color: var(--border);
            color: var(--muted);
        }

        .btn-ghost:hover:not(:disabled) {
            color: var(--text);
            border-color: rgba(99, 102, 241, 0.4);
            background: rgba(99, 102, 241, 0.16);
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn:hover:not(:disabled) {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn-danger:hover:not(:disabled) {
            background: #f87171;
            border-color: #f87171;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            color: var(--text);
        }

        .table th,
        .table td {
            border: 1px solid var(--border);
            padding: 8px;
            vertical-align: top;
            font-size: 14px;
        }

        .table th {
            background: rgba(99, 102, 241, 0.12);
            text-align: left;
            color: var(--fg);
        }

        input,
        select {
            border: 1px solid var(--border-soft);
            border-radius: 6px;
            padding: 6px 8px;
            background: var(--surface-alt);
            color: var(--text);
        }

        input:focus,
        select:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
        }
    </style>
    @stack('head')
</head>

<body>
    <div class="container">
        <div class="nav" style="flex-wrap:wrap;">
            <a href="{{ route('admin.intakes') }}">문의 목록</a>
            <span class="muted">·</span>
            <a href="{{ route('admin.success-stories.index') }}">섭외 사례</a>
            <span class="muted">·</span>
            <a href="{{ route('admin.artists.index') }}">아티스트</a>
            <span class="muted">·</span>
            <a href="{{ route('admin.taxonomies.index') }}">분류 관리</a>
            <span class="muted">·</span>
            <a href="{{ route('dashboard') }}">대시보드</a>
        </div>

        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>
