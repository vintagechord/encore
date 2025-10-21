@extends('layouts.admin')

@php
$isEdit = $mode === 'edit';
$title = $isEdit ? '섭외 사례 수정' : '새 섭외 사례 등록';
@endphp

@section('title', $title)

@section('content')
<h1>{{ $title }}</h1>

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form method="post" action="{{ $isEdit ? route('admin.success-stories.update', $story) : route('admin.success-stories.store') }}" enctype="multipart/form-data" style="display:grid; gap:14px;">
        @csrf
        @if($isEdit) @method('put') @endif

        <div style="display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
            <label>
                <span class="muted">카테고리</span>
                <input type="text" name="category" value="{{ old('category', $story->category) }}" placeholder="예: 섭외 확정">
            </label>

            <label>
                <span class="muted">역할/분류</span>
                <input type="text" name="role" value="{{ old('role', $story->role) }}" placeholder="예: 연예인, 밴드, MC">
            </label>

            <label>
                <span class="muted">진열 순서</span>
                <input type="number" name="display_order" min="0" value="{{ old('display_order', $story->display_order) }}">
            </label>

            <label style="display:flex; gap:8px; align-items:center; margin-top:22px;">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $story->is_active))>
                <span>메인 페이지에 노출</span>
            </label>
        </div>

        <label>
            <span class="muted">사례 제목 <span style="color:#fca5a5">*</span></span>
            <input type="text" name="title" value="{{ old('title', $story->title) }}" required>
        </label>

        <label>
            <span class="muted">설명</span>
            <textarea name="summary" rows="3" placeholder="간단한 진행 후기나 특징을 남겨주세요.">{{ old('summary', $story->summary) }}</textarea>
        </label>

        <div style="display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
            <label>
                <span class="muted">행사명</span>
                <input type="text" name="event_name" value="{{ old('event_name', $story->event_name) }}">
            </label>

            <label>
                <span class="muted">행사일</span>
                <input type="date" name="event_date" value="{{ old('event_date', optional($story->event_date)->format('Y-m-d')) }}">
            </label>

            <label>
                <span class="muted">장소</span>
                <input type="text" name="location" value="{{ old('location', $story->location) }}">
            </label>
        </div>

        <label>
            <span class="muted">대표 이미지</span>
            <input type="file" name="thumbnail" accept="image/*">
        </label>

        @if($story->thumbnail_path)
            <div style="display:flex; gap:12px; align-items:center;">
                <img src="{{ asset('storage/'.$story->thumbnail_path) }}" alt="현재 이미지" style="width:140px;height:auto;border-radius:8px;">
                <label style="display:flex; gap:6px; align-items:center;">
                    <input type="checkbox" name="remove_thumbnail" value="1">
                    <span>이미지 제거</span>
                </label>
            </div>
        @endif

        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <button class="btn btn-primary" type="submit">{{ $isEdit ? '수정 완료' : '등록하기' }}</button>
            <a class="btn btn-ghost" href="{{ route('admin.success-stories.index') }}">목록으로</a>
        </div>
    </form>
</div>
@endsection
