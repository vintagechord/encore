<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>추천 옵션 미리보기</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
                radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
                #0b090a;
            --surface: #151012;
            --card: #1a1316;
            --card-alt: #1b1316;
            --border: #2a1c22;
            --border-soft: #352029;
            --text: #f7f1e9;
            --muted: #b6a89a;
            --accent: #f3c652;
            --accent-hover: #f0b840;
            --font-sans: "Noto Sans KR", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
            --font-display: "Noto Serif KR", "Apple SD Gothic Neo", "Malgun Gothic", serif;
        }

        [data-theme="light"] {
            --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%),
                linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%);
            --surface: #fffdf8;
            --card: #fff9f0;
            --card-alt: #f6ecdd;
            --border: #e6d4c0;
            --border-soft: #d7c5b3;
            --text: #2b1b1b;
            --muted: #6b5b53;
            --accent: #e3b648;
            --accent-hover: #d5a63b;
        }

        body {
            font-family: var(--font-sans);
            margin: 24px;
            line-height: 1.5;
            background: var(--bg);
            color: var(--text);
        }

        h1 {
            margin: 0 0 6px;
            font-size: 22px;
        }

        a {
            color: var(--accent);
        }

        a:hover {
            color: var(--accent-hover);
        }

        .muted {
            color: var(--muted);
        }

        .btn {
            padding: 9px 12px;
            border-radius: 10px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #1b130f;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease, border-color .2s ease;
        }

        .btn:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn.secondary {
            background: transparent;
            color: var(--accent);
        }

        .row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
            background: var(--surface);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        }

        .option {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
            margin: 12px 0;
            background: var(--card);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 10px;
        }

        .chip {
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 8px 10px;
            background: var(--card-alt);
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
        }

        .right {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .kbd {
            font: 12px/1.2 ui-monospace, SFMono-Regular, Menlo, monospace;
            background: var(--card-alt);
            border: 1px solid var(--border-soft);
            border-bottom-width: 2px;
            border-radius: 6px;
            padding: 2px 6px;
            color: var(--muted);
        }
    </style>
</head>

<body>
    <script>(function(){try{var t=(localStorage.getItem('enc_theme')==='light') ? 'light' : 'dark';document.documentElement.setAttribute('data-theme', t);}catch(e){}})();</script>
    <div class="header">
        <div>
            <h1>추천 옵션</h1>
            <div class="muted">요청 ID #{{ $intake->id }} &middot; seed={{ $seed }}</div>
        </div>
        <div class="row">
            <a class="btn secondary" href="{{ route('home') }}">홈으로</a>
            <a class="btn secondary" href="{{ route('inquiry.create') }}">다시 문의하기</a>
        </div>
    </div>

    <div class="card">
        <strong>요청 요약</strong>
        <div class="row" style="margin-top:6px">
            <span class="kbd">일정</span>
            <span>{{ optional($intake->event_start)->format('Y-m-d') }}
                @if($intake->event_end && $intake->event_end != $intake->event_start)
                ~ {{ optional($intake->event_end)->format('Y-m-d') }}
                @endif
            </span>
            <span class="kbd">예산</span>
            <span>
                @php
                $fmt = fn($n) => $n ? number_format($n).'원' : '미정';
                @endphp
                {{ $fmt($intake->budget_min) }} ~ {{ $fmt($intake->budget_max) }}
            </span>
            @if(is_array($intake->genre_counts) && $intake->genre_counts)
            <span class="kbd">구성</span>
            <span>
                @foreach($intake->genre_counts as $g=>$n)
                {{ $g }}: {{ $n }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </span>
            @endif
        </div>
    </div>

    <!-- 재생성/고정/제외를 유지하기 위한 폼 -->
    <form id="regenForm" class="card" method="get" action="{{ route('inquiry.options', ['intake' => $intake->id]) }}">
        <input type="hidden" name="seed" id="seedInput" value="{{ $seed }}">
        <input type="hidden" name="count" value="{{ $optionCount }}">
        <div id="persist"></div>

        <div class="row">
            <button type="button" class="btn" id="btnRegen">다른 옵션 보기(재생성)</button>
            <span class="muted">체크한 “고정/제외”는 유지됩니다.</span>
        </div>
    </form>

    @foreach($options as $idx => $opt)
    <div class="option">
        <div class="row" style="justify-content:space-between;align-items:center;margin-bottom:6px">
            <strong>옵션 {{ $idx+1 }}</strong>
            <span class="muted">예상 소계: {{ number_format($opt['subtotal']) }}원</span>
        </div>
        <div class="grid">
            @foreach($opt['artist_ids'] as $aid)
            @php $a = $artists[$aid] ?? null; @endphp
            <div class="chip">
                <div>
                    <div><strong>#{{ $aid }}</strong> {{ $a?->stage_name ?? 'Unknown' }}</div>
                    <div class="muted" style="font-size:12px">
                        @if($a)
                        @php $fee = $a->fee_range ?? null; @endphp
                        @if($fee)
                          {{ number_format($fee['min'] ?? 0) }} ~ {{ number_format($fee['max'] ?? 0) }}원
                        @endif
                        @php
                          $genreOut = '';
                          if (is_array($a->genres ?? null))      { $genreOut = implode(', ', array_filter($a->genres)); }
                          elseif (method_exists($a,'genresRelation')) { $genreOut = $a->genresRelation->pluck('name')->implode(', '); }
                        @endphp
                        @if($genreOut !== '') &middot; 장르: {{ $genreOut }} @endif
                        @endif
                    </div>
                </div>
                <div class="right">
                    <label class="muted" style="font-size:12px">
                        <input type="checkbox" class="locker" data-id="{{ $aid }}" {{ in_array($aid, $locked) ? 'checked' : '' }}>
                        고정
                    </label>
                    <label class="muted" style="font-size:12px">
                        <input type="checkbox" class="excluder" data-id="{{ $aid }}" {{ in_array($aid, $excluded) ? 'checked' : '' }}>
                        제외
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <script>
        (function() {
            const persistBox = document.getElementById('persist');

            function rebuildHidden() {
                persistBox.innerHTML = '';
                document.querySelectorAll('.locker:checked').forEach(cb => {
                    const i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = 'locked[]';
                    i.value = cb.dataset.id;
                    persistBox.appendChild(i);
                });
                document.querySelectorAll('.excluder:checked').forEach(cb => {
                    const i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = 'exclude[]';
                    i.value = cb.dataset.id;
                    persistBox.appendChild(i);
                });
            }
            rebuildHidden();

            // 고정/제외 변경 시 hidden 재구성
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('locker') || e.target.classList.contains('excluder')) {
                    // 서로 배타 처리: 고정 & 제외가 동시에 체크되지 않게
                    if (e.target.classList.contains('locker') && e.target.checked) {
                        const x = document.querySelector('.excluder[data-id="' + e.target.dataset.id + '"]');
                        if (x) x.checked = false;
                    }
                    if (e.target.classList.contains('excluder') && e.target.checked) {
                        const l = document.querySelector('.locker[data-id="' + e.target.dataset.id + '"]');
                        if (l) l.checked = false;
                    }
                    rebuildHidden();
                }
            });

            // 재생성: seed 갱신 후 제출
            document.getElementById('btnRegen').addEventListener('click', () => {
                document.getElementById('seedInput').value = Date.now().toString();
                rebuildHidden();
                document.getElementById('regenForm').submit();
            });
        })();
    </script>
</body>

</html>
