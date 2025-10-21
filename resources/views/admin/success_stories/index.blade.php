@extends('layouts.admin')

@section('title', '섭외 사례 관리')

@section('content')
<h1>섭외 사례 관리</h1>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<div style="margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; gap:12px;">
    <div class="muted">메인 페이지에 노출할 섭외 성공 사례를 관리합니다.</div>
    <a class="btn btn-primary" href="{{ route('admin.success-stories.create') }}">새 사례 등록</a>
</div>

@if($stories->isEmpty())
    <div class="card">
        <p>등록된 섭외 사례가 없습니다. “새 사례 등록” 버튼을 눌러 추가하세요.</p>
    </div>
@else
    <div class="card" style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:70px;">진열</th>
                    <th style="width:160px;">썸네일</th>
                    <th>제목 / 카테고리</th>
                    <th style="width:160px;">행사 정보</th>
                    <th style="width:90px;">상태</th>
                    <th style="width:160px;">관리</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stories as $story)
                <tr>
                    <td>{{ $story->display_order }}</td>
                    <td>
                        @if($story->thumbnail_path)
                            <img src="{{ asset('storage/'.$story->thumbnail_path) }}" alt="{{ $story->title }} 이미지" style="width:140px;height:auto;border-radius:8px;">
                        @else
                            <span class="muted">이미지 없음</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $story->title }}</strong>
                        <div class="muted" style="margin-top:4px">
                            {{ $story->category }} @if($story->role) · {{ $story->role }} @endif
                        </div>
                        @if($story->summary)
                            <div style="margin-top:6px;font-size:13px;">{!! nl2br(e($story->summary)) !!}</div>
                        @endif
                    </td>
                    <td>
                        <div><strong>행사명</strong> {{ $story->event_name ?: '—' }}</div>
                        <div><strong>일자</strong> {{ optional($story->event_date)->format('Y-m-d') ?: '—' }}</div>
                        <div><strong>장소</strong> {{ $story->location ?: '—' }}</div>
                    </td>
                    <td>{{ $story->is_active ? '노출' : '숨김' }}</td>
                    <td>
                        <div style="display:flex; gap:6px; flex-direction:column;">
                            <a class="btn btn-sm" href="{{ route('admin.success-stories.edit', $story) }}">수정</a>
                            <form method="post" action="{{ route('admin.success-stories.destroy', $story) }}" onsubmit="return confirm('삭제할까요?');">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">삭제</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
