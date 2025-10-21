@extends('layouts.admin')

@section('content')
<h1>아티스트 관리</h1>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<form method="get" class="mb-3" style="display:flex; gap:8px; flex-wrap:wrap;">
    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="이름 검색">
    <select name="discipline_id">
        <option value="">분야 전체</option>
        @foreach($disciplines as $d)
        <option value="{{ $d->id }}" @selected(($filters['discipline_id'] ?? null)==$d->id)>{{ $d->name }}</option>
        @endforeach
    </select>
    <input type="number" name="min_fame" value="{{ $filters['min_fame'] ?? '' }}" placeholder="최소 유명세(0-100)" min="0" max="100">
    <input type="number" name="max_fame" value="{{ $filters['max_fame'] ?? '' }}" placeholder="최대 유명세(0-100)" min="0" max="100">

    <select name="genre_ids[]" multiple size="3">
        @foreach($genres as $g)
        <option value="{{ $g->id }}" @selected(in_array($g->id, (array)($filters['genre_ids'] ?? [])))>
            {{ $g->name }}
        </option>
        @endforeach
    </select>

    <select name="tag_ids[]" multiple size="3">
        @foreach($tags as $t)
        <option value="{{ $t->id }}" @selected(in_array($t->id, (array)($filters['tag_ids'] ?? [])))>
            {{ $t->name }}
        </option>
        @endforeach
    </select>

    <label><input type="checkbox" name="is_active" value="1" @checked(($filters['is_active'] ?? null)===true)> 사용중만</label>
    <button class="btn btn-primary" type="submit">필터</button>
    <a class="btn" href="{{ route('admin.artists.index') }}">초기화</a>
    <a class="btn btn-success" href="{{ route('admin.artists.create') }}">새 아티스트</a>
</form>

<table class="table">
    <thead>
        <tr>
            <th>이름</th>
            <th>분야/장르</th>
            <th>태그</th>
            <th>유명세</th>
            <th>요율</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($artists as $a)
        <tr>
            <td>{{ $a->stage_name }}
                <div class="text-muted">{{ $a->legal_name }}</div>
            </td>
            <td>
                <div>{{ $a->discipline->name ?? '-' }}</div>
                <div class="text-muted">
                    @foreach($a->genres as $g) <span>{{ $g->name }}</span>@if(!$loop->last), @endif @endforeach
                </div>
            </td>
            <td class="text-muted">
                @foreach($a->tags as $t) <span>#{{ $t->name }}</span>@if(!$loop->last), @endif @endforeach
            </td>
            <td>{{ $a->fame_score }}</td>
            <td class="text-muted">
                @php $fee = $a->fees->first(); @endphp
                @if($fee)
                {{ $fee->currency }} {{ number_format($fee->min_fee) }} ~ {{ number_format($fee->max_fee) }} ({{ $fee->unit }})
                @else
                -
                @endif
            </td>
            <td>
                <a class="btn btn-sm" href="{{ route('admin.artists.edit',$a) }}">수정</a>
                <form method="post" action="{{ route('admin.artists.destroy',$a) }}" style="display:inline" onsubmit="return confirm('삭제할까요?')">
                    @csrf @method('delete')
                    <button class="btn btn-sm btn-danger" type="submit">삭제</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">결과 없음</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $artists->links() }}
@endsection