<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>@yield('title', '관리자')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: -apple-system, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            margin: 24px;
            color: #111
        }

        a {
            color: #2563eb;
            text-decoration: none
        }

        a:hover {
            text-decoration: underline
        }

        .container {
            max-width: 1200px;
            margin: 0 auto
        }

        .nav {
            margin-bottom: 16px;
            display: flex;
            gap: 12px;
            align-items: center
        }

        .muted {
            color: #6b7280
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            background: #fff
        }

        .alert {
            padding: 10px 12px;
            border-radius: 6px;
            margin: 10px 0
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #10b98133
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #ef444433
        }

        .btn {
            display: inline-block;
            padding: 6px 10px;
            border: 1px solid #111;
            background: #111;
            color: #fff;
            border-radius: 6px;
            cursor: pointer
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb
        }

        .btn-danger {
            background: #ef4444;
            border-color: #ef4444
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px
        }

        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            vertical-align: top;
            font-size: 14px
        }

        .table th {
            background: #f9fafb;
            text-align: left
        }

        input,
        select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 8px
        }
    </style>
    @stack('head')
</head>

<body>
    <div class="container">
        <div class="nav">
            <a href="{{ route('admin.intakes') }}">문의 목록</a>
            <span class="muted">/</span>
            <a href="{{ route('dashboard') }}">대시보드</a>
        </div>

        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>