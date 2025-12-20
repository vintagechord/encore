@extends('layouts.admin')

@section('content')
<h1>샘플/후기 {{ $mode === 'edit' ? '수정' : '생성' }}</h1>

@if($errors->any())
  <div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="post" action="{{ $mode==='edit' ? route('admin.testimonials.update',$testimonial) : route('admin.testimonials.store') }}" style="display:grid; gap:12px; max-width:720px;">
  @csrf
  @if($mode==='edit') @method('patch') @endif

  <label>유형
    <input type="text" name="kind" value="{{ old('kind', $testimonial->kind) }}" placeholder="샘플 문의 / 후기" required>
  </label>

  <label>제목
    <input type="text" name="title" value="{{ old('title', $testimonial->title) }}" required>
  </label>

  <label>내용
    <textarea name="body" rows="4" required>{{ old('body', $testimonial->body) }}</textarea>
  </label>

  <label>메타(좌측 텍스트)
    <input type="text" name="meta" value="{{ old('meta', $testimonial->meta) }}" placeholder="예상 응답: ... / 마케팅팀 B실장">
  </label>

  <label>별점 (0~5, 소수 1자리)
    <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $testimonial->rating) }}">
  </label>

  <div style="display:flex; gap:12px; align-items:center;">
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active))> 활성</label>
    <label>표시순서 <input type="number" name="display_order" min="0" value="{{ old('display_order', $testimonial->display_order) }}" style="width:100px"></label>
  </div>

  <div style="display:flex; gap:8px;">
    <button class="btn btn-primary">저장</button>
    <a class="btn" href="{{ route('admin.testimonials.index') }}">목록</a>
  </div>
</form>
@endsection
