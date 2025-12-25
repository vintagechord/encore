<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>링크가 만료되었습니다</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="dark light">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <style>
        :root {
            --bg: radial-gradient(1200px 520px at 12% -8%, rgba(243, 198, 82, 0.18), rgba(10, 10, 10, 0) 60%),
                radial-gradient(900px 480px at 92% 2%, rgba(255, 255, 255, 0.05), rgba(10, 10, 10, 0) 55%),
                #0a0a0a;
            --fg: #f7f4ee;
            --muted: #b4b0a8;
            --card: #121212;
            --border: #2a2a2a;
            --accent: #f3c652;
            --accent-hover: #e6b940;
            --btn: #f3c652;
            --btn-hover: #e6b940;
            --btn-text: #17120a;
            --font-sans: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
        }

        [data-theme="light"] {
            --bg: radial-gradient(980px 360px at 10% -6%, rgba(243, 198, 82, 0.14), rgba(255, 255, 255, 0) 60%),
                linear-gradient(180deg, #ffffff 0%, #f7f3ea 100%);
            --fg: #1c1b19;
            --muted: #6b655c;
            --card: #ffffff;
            --border: #e3ddd2;
            --accent: #f3c652;
            --accent-hover: #e6b940;
            --btn: #f3c652;
            --btn-hover: #e6b940;
            --btn-text: #17120a;
        }

        body {
            font-family: var(--font-sans);
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
            background: var(--btn);
            color: var(--btn-text);
            text-decoration: none;
            border: 1px solid var(--btn);
            transition: background .2s ease, border-color .2s ease;
        }

        a.btn:hover {
            background: var(--btn-hover);
            border-color: var(--btn-hover);
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
    <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light') ? 'light' : 'dark';document.documentElement.setAttribute('data-theme', t);}catch(e){}})();</script>
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
