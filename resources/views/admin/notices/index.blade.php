@extends('layouts.admin')

@push('head')
  <style>
    .adm-toolbar{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:8px 0 10px; }
    .adm-toolbar .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .adm-toolbar .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }
    table.adm-table{ width:100%; border-collapse:collapse; background: var(--surface, var(--card)); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
    table.adm-table th, table.adm-table td{ padding:12px; border-bottom:1px solid var(--border); vertical-align:top; }
    table.adm-table thead th{ background: rgba(141,31,45,.16); text-align:left; }
    .row-actions{ display:flex; gap:6px; flex-wrap:wrap; }
    .btn.sm{ padding:6px 10px; border-radius:8px; font-size:13px; }
    .btn.danger{ background:#ef4444; border-color:#ef4444; }
    .btn.danger:hover{ background:#f87171; border-color:#f87171; }
  </style>
@endpush

@section('content')
  <h1>공지사항</h1>
  @if(session('ok')) <div class="alert alert-success">저장되었습니다.</div> @endif
  <div class="adm-toolbar">
    <a class="btn" href="{{ route('admin.notices.create') }}">새 공지</a>
  </div>
  <table class="adm-table">
    <thead>
      <tr>
        <th style="width:60px">ID</th>
        <th>제목</th>
        <th style="width:160px">게시일</th>
        <th style="width:120px">공개</th>
        <th style="width:140px"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($notices as $n)
      <tr>
        <td>{{ $n->id }}</td>
        <td>{{ $n->title }}</td>
        <td>{{ optional($n->published_at)->format('Y-m-d') }}</td>
        <td>{{ $n->is_published ? 'YES' : 'NO' }}</td>
        <td>
          <div class="row-actions">
          <a class="btn sm" href="{{ route('admin.notices.edit', $n) }}">수정</a>
          <form method="post" action="{{ route('admin.notices.destroy', $n) }}" onsubmit="return confirm('삭제할까요?')">
            @csrf @method('delete')
            <button class="btn sm danger" type="submit">삭제</button>
          </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5">데이터가 없습니다.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="margin-top:10px">{{ $notices->links('pagination::encore') }}</div>
@endsection
