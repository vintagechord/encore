@extends('layouts.admin')

@section('content')
<h1>배너 {{ $mode === 'edit' ? '수정' : '생성' }}</h1>

@if($errors->any())
  <div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="post" action="{{ $mode==='edit' ? route('admin.banners.update',$banner) : route('admin.banners.store') }}" enctype="multipart/form-data" style="display:grid; gap:12px; max-width:720px;">
  @csrf
  @if($mode==='edit') @method('patch') @endif

  <label>제목
    <input type="text" name="title" value="{{ old('title', $banner->title) }}" required>
  </label>

  <label>이동 링크(URL)
    <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://...">
  </label>

  <label>이미지(권장 1200x400)
    <input type="file" name="image">
  </label>
  @if($banner->image_path)
    <img src="{{ asset('storage/'.$banner->image_path) }}" alt="현재 이미지" style="max-width:360px; border-radius:8px; border:1px solid var(--border)">
  @endif

  <div style="display:flex; gap:12px; align-items:center;">
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active))> 활성</label>
    <label>표시순서 <input type="number" name="display_order" min="0" value="{{ old('display_order', $banner->display_order) }}" style="width:100px"></label>
  </div>

  <div style="display:flex; gap:8px;">
    <button class="btn btn-primary">저장</button>
    <a class="btn" href="{{ route('admin.banners.index') }}">목록</a>
  </div>
</form>
@endsection

