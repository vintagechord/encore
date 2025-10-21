<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>링크가 만료되었습니다</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        :root {
            --bg: #050912;
            --fg: #e5ecff;
            --muted: #97a6c9;
            --card: #0f1729;
            --border: #1f2b41;
            --accent: #6366f1;
            --accent-hover: #818cf8;
        }

        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Apple SD Gothic Neo, Noto Sans KR, sans-serif;
            line-height: 1.55;
            margin: 40px;
            color: var(--fg);
            background: var(--bg);
        }

        h1 {
            font-size: 24px;
            margin: 0 0 10px;
        }

        p {
            margin: 8px 0;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--card);
            padding: 18px 20px;
            max-width: 720px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .muted {
            color: var(--muted);
        }

        a.btn {
            display: inline-block;
            margin-top: 14px;
            padding: 8px 12px;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            border: 1px solid var(--accent);
            transition: background .2s ease, border-color .2s ease;
        }

        a.btn:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        code {
            background: rgba(148, 163, 208, 0.12);
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid rgba(148, 163, 208, 0.25);
        }
    </style>
</head>

<body>
    <h1>링크가 만료되었습니다</h1>
    <div class="card">
        <p>요청하신 공개 링크는 더 이상 유효하지 않습니다.</p>
        <ul>
            <li class="muted">링크가 <strong>회수(공유 중단)</strong>되었거나,</li>
            <li class="muted"><strong>재생성(토큰 회전)</strong>으로 주소가 변경되었거나,</li>
            <li class="muted">주소가 잘못 입력되었을 수 있습니다.</li>
        </ul>

        @isset($token)
        <p class="muted" style="margin-top:10px">참조 토큰: <code>{{ $token }}</code></p>
        @endisset

        <p>
            <a class="btn" href="{{ route('inquiry.create') }}">새 문의 작성</a>
        </p>
    </div>
</body>

</html>
