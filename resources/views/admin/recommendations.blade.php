<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>추천안 보기 #{{ $intake->id }}</title>
    <style>
        body {
            font-family: -apple-system, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            margin: 24px;
            line-height: 1.5
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px
        }

        .muted {
            color: #6b7280;
            font-size: 12px
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #111;
            border-radius: 8px;
            background: #111;
            color: #fff;
            text-decoration: none;
            cursor: pointer
        }

        .btn+.btn {
            margin-left: 6px
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin: 14px 0
        }

        .row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin: 8px 0
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 14px;
            vertical-align: top
        }

        th {
            background: #f9fafb;
            text-align: left
        }

        .right {
            text-align: right
        }

        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 6px
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