@extends('layouts.admin')

@section('content')
<h1>배너 관리</h1>

@if(isset($tableMissing) && $tableMissing)
  <div class="alert alert-danger">banners 테이블이 없습니다. 마이그레이션을 실행해 주세요.</div>
@endif

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<p style="margin-bottom:10px;">
  <a class="btn btn-primary" href="{{ route('admin.banners.create') }}">새 배너</a>
  <a class="btn" href="{{ route('admin.intakes') }}">관리 홈</a>
  <a class="btn" href="{{ route('home') }}" target="_blank" rel="noopener">메인 열기</a>
  <span class="muted">활성 배너는 메인 페이지 상단에 표시됩니다.</span>
  </p>

<table class="table">
  <thead>
    <tr>
      <th style="width:60px">ID</th>
      <th>제목</th>
      <th>이미지</th>
      <th style="width:100px">활성</th>
      <th style="width:120px">표시순서</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  @forelse($banners as $b)
    <tr>
      <td>{{ $b->id }}</td>
      <td>{{ $b->title }}</td>
      <td>
        @if($b->image_path)
          <img src="{{ asset('storage/'.$b->image_path) }}" alt="thumb" style="max-width:160px;border-radius:8px;border:1px solid var(--border)">
        @endif
      </td>
      <td>{{ $b->is_active ? 'Y' : 'N' }}</td>
      <td>{{ $b->display_order }}</td>
      <td>
        <a class="btn btn-sm" href="{{ route('admin.banners.edit', $b) }}">수정</a>
        <form method="post" action="{{ route('admin.banners.destroy', $b) }}" style="display:inline" onsubmit="return confirm('삭제할까요?');">
          @csrf @method('delete')
          <button class="btn btn-sm btn-danger">삭제</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6">데이터가 없습니다.</td></tr>
  @endforelse
  </tbody>
 </table>
@endsection

