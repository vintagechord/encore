<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>추천안 보기 #{{ $intake->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&family=Noto+Serif+KR:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
                radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
                #0b090a;
            --surface: #151012;
            --surface-alt: #1b1316;
            --border: #2a1c22;
            --text: #f7f1e9;
            --muted: #b6a89a;
            --accent: #f3c652;
            --accent-hover: #f0b840;
            --font-sans: "Noto Sans KR", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
        }

        body {
            font-family: var(--font-sans);
            margin: 24px;
            line-height: 1.5;
            background: var(--bg);
            color: var(--text);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .muted {
            color: var(--muted);
            font-size: 12px;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid var(--accent);
            border-radius: 8px;
            background: var(--accent);
            color: #1b130f;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease, border-color .2s ease;
        }

        .btn:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn+.btn {
            margin-left: 6px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin: 14px 0;
            background: var(--surface);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        }

        .row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin: 8px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            background: var(--surface-alt);
        }

        th,
        td {
            border-bottom: 1px solid var(--border);
            padding: 8px;
            font-size: 14px;
            vertical-align: top;
        }

        th {
            background: rgba(99, 102, 241, 0.14);
            text-align: left;
            color: var(--text);
        }

        .right {
            text-align: right;
        }

        code {
            background: rgba(148, 163, 208, 0.16);
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid rgba(148, 163, 208, 0.28);
            color: var(--muted);
        }
    </style>
</head>

<body>
    <h1>추천안 보기 <span class="muted">#{{ $intake->id }} · {{ $intake->contact_name }} ({{ $intake->contact_email }})</span></h1>

    <div class="row">
        <a class="btn" href="{{ route('admin.intakes') }}">← 목록으로</a>
        <a class="btn" href="{{ route('admin.recommendations.json', ['intake' => $intake->id]) }}" target="_blank" rel="noopener">JSON 보기</a>
        <a class="btn" href="{{ route('inquiry.options', ['intake' => $intake->id]) }}">옵션 다시 생성</a>
    </div>

    @if($sets->isEmpty())
    <div class="card">
        <p>아직 저장된 추천안이 없습니다.</p>
        <p class="muted">“옵션 다시 생성”을 눌러 추천 구성을 만들어 보세요.</p>
    </div>
    @else
    @foreach($sets as $s)
    <div class="card">
        <div class="row">
            <strong>세트명:</strong> {{ $s->label ?: '무제' }}
            <span class="muted">| ID: {{ $s->id }}</span>
            @if($s->sent_at)
            <span class="muted">| 발송: {{ \Illuminate\Support\Carbon::parse($s->sent_at, 'UTC')->toIso8601String() }}</span>
            @endif
            @if($s->public_token)
            <span class="muted">| 공개 링크: </span>
            <a href="{{ url('/r/'.$s->public_token) }}" target="_blank" rel="noopener">{{ url('/r/'.$s->public_token) }}</a>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:70px">#</th>
                    <th>아티스트</th>
                    <th>메모</th>
                    <th class="right" style="width:140px">예상비용(원)</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @forelse(($s->items ?? []) as $idx => $it)
                @php
                $artist = $artists[$it['artist_id'] ?? 0] ?? null;
                $fee = (int)($it['fee'] ?? 0);
                $total += $fee;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        @if($artist)
                        {{ $artist->name_kr ?? $artist->name ?? ('#'.$artist->id) }}
                        <span class="muted">(<code>ID {{ $artist->id }}</code>)</span>
                        @else
                        <em class="muted">알 수 없음</em>
                        @if(!empty($it['artist_id'])) <span class="muted">(<code>ID {{ $it['artist_id'] }}</code>)</span> @endif
                        @endif
                    </td>
                    <td>{{ $it['note'] ?? '' }}</td>
                    <td class="right">{{ number_format($fee) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="muted">항목이 없습니다.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="right">합계</th>
                    <th class="right">{{ number_format($s->total_cost ?? $total) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach
    @endif
</body>

</html>
