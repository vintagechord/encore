@extends('layouts.admin')

@section('title', '섭외 사례 관리')

@push('head')
  <style>
    .adm-toolbar{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:8px 0 10px; }
    .adm-toolbar .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .adm-toolbar .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }
    table.adm-table{ width:100%; border-collapse:collapse; background: var(--surface, var(--card)); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
    table.adm-table th, table.adm-table td{ padding:12px; border-bottom:1px solid var(--border); vertical-align:top; }
    table.adm-table thead th{ background: rgba(141,31,45,.16); text-align:left; }
    .row-actions{ display:flex; gap:6px; flex-wrap:wrap; }
    .btn.sm{ padding:6px 10px; border-radius:8px; font-size:13px; border:1px solid var(--accent); background: var(--accent); color:#fff; }
    .btn.danger{ background:#ef4444; border-color:#ef4444; }
    .btn.danger:hover{ background:#f87171; border-color:#f87171; }
    .muted{ color: var(--muted); }
  </style>
@endpush

@section('content')
  <h1>섭외 사례 관리</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <div class="adm-toolbar">
    <a class="btn" href="{{ route('admin.success-stories.create') }}">새 사례 등록</a>
    <span class="muted">메인 페이지에 노출할 사례를 관리합니다.</span>
  </div>

  @if($stories->isEmpty())
    <div class="card"><p>등록된 섭외 사례가 없습니다. “새 사례 등록” 버튼을 눌러 추가하세요.</p></div>
  @else
    <table class="adm-table">
      <thead>
        <tr>
          <th style="width:70px">진열</th>
          <th style="width:200px">썸네일</th>
          <th>제목/카테고리</th>
          <th style="width:260px">행사 정보</th>
          <th style="width:100px">상태</th>
          <th style="width:160px">관리</th>
        </tr>
      </thead>
      <tbody>
        @foreach($stories as $story)
        <tr>
          <td>{{ $story->display_order }}</td>
          <td>
            @if($story->thumbnail_path)
              <img src="{{ asset('storage/'.$story->thumbnail_path) }}" alt="{{ $story->title }} 이미지" style="width:180px;height:auto;border-radius:10px;border:1px solid var(--border)">
            @else
              <span class="muted">이미지 없음</span>
            @endif
          </td>
          <td>
            <strong>{{ $story->title }}</strong>
            <div class="muted" style="margin-top:4px">{{ $story->category }} @if($story->role) · {{ $story->role }} @endif</div>
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
            <div class="row-actions">
              <a class="btn sm" href="{{ route('admin.success-stories.edit', $story) }}">수정</a>
              <form method="post" action="{{ route('admin.success-stories.destroy', $story) }}" onsubmit="return confirm('삭제할까요?');">
                @csrf @method('delete')
                <button class="btn sm danger" type="submit">삭제</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection
