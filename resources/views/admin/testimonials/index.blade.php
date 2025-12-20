@extends('layouts.admin')

@push('head')
  <style>
    .adm-toolbar{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:8px 0 10px; }
    .adm-toolbar .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .adm-toolbar .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }
    table.adm-table{ width:100%; border-collapse:collapse; background: var(--surface, var(--card)); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
    table.adm-table th, table.adm-table td{ padding:12px; border-bottom:1px solid var(--border); vertical-align:top; }
    table.adm-table thead th{ background: rgba(99,102,241,.14); text-align:left; }
    .row-actions{ display:flex; gap:6px; flex-wrap:wrap; }
    .btn.sm{ padding:6px 10px; border-radius:8px; font-size:13px; }
    .btn.danger{ background:#ef4444; border-color:#ef4444; }
    .btn.danger:hover{ background:#f87171; border-color:#f87171; }
    .muted{ color: var(--muted); }
  </style>
@endpush

@section('content')
<h1>샘플 문의/후기</h1>

@if(isset($tableMissing) && $tableMissing)
  <div class="alert alert-danger">testimonials 테이블이 없습니다. 마이그레이션을 실행해 주세요.</div>
@endif

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<div class="adm-toolbar">
  <a class="btn" href="{{ route('admin.testimonials.create') }}">새 샘플/후기</a>
  <a class="btn ghost" href="{{ route('admin.intakes') }}">관리 홈</a>
  <a class="btn ghost" href="{{ route('home') }}" target="_blank" rel="noopener">메인 열기</a>
  <span class="muted">활성 항목은 메인 하단에 흐르는 탭으로 노출됩니다.</span>
</div>

<table class="adm-table">
  <thead>
    <tr>
      <th style="width:60px">ID</th>
      <th style="width:120px">유형</th>
      <th>제목</th>
      <th>내용</th>
      <th style="width:160px">메타</th>
      <th style="width:80px">별점</th>
      <th style="width:80px">활성</th>
      <th style="width:100px">표시순서</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  @forelse($testimonials as $t)
    <tr>
      <td>{{ $t->id }}</td>
      <td>{{ $t->kind }}</td>
      <td>{{ $t->title }}</td>
      <td>{{ \Illuminate\Support\Str::limit($t->body, 60) }}</td>
      <td>{{ $t->meta }}</td>
      <td>{{ $t->rating !== null ? number_format($t->rating, 1) : '-' }}</td>
      <td>{{ $t->is_active ? 'Y' : 'N' }}</td>
      <td>{{ $t->display_order }}</td>
      <td>
        <div class="row-actions">
          <a class="btn sm" href="{{ route('admin.testimonials.edit', $t) }}">수정</a>
          <form method="post" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('삭제할까요?');">
            @csrf @method('delete')
            <button class="btn sm danger">삭제</button>
          </form>
        </div>
      </td>
    </tr>
  @empty
    <tr><td colspan="9">데이터가 없습니다.</td></tr>
  @endforelse
  </tbody>
 </table>
@endsection
