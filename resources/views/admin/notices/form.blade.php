@extends('layouts.admin')

@section('content')
  <h1>공지 {{ $notice->exists ? '수정' : '작성' }}</h1>
  @if(session('ok')) <div class="alert alert-success">저장되었습니다.</div> @endif
  <form method="post" action="{{ $notice->exists ? route('admin.notices.update',$notice) : route('admin.notices.store') }}">
    @csrf
    @if($notice->exists) @method('patch') @endif
    <div class="card" style="display:grid; gap:8px; margin-bottom:10px">
      <label>제목
        <input type="text" name="title" value="{{ old('title', $notice->title) }}" required>
      </label>
      <label>내용
        <textarea name="body" rows="10" style="width:100%">{{ old('body', $notice->body) }}</textarea>
      </label>
      <label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $notice->is_published))> 공개</label>
      <label>게시일(선택)
        <input type="date" name="published_at" value="{{ old('published_at', optional($notice->published_at)->toDateString()) }}">
      </label>
      <div>
        <button class="btn" type="submit">저장</button>
        <a class="btn btn-ghost" href="{{ route('admin.notices.index') }}">목록</a>
      </div>
    </div>
  </form>
@endsection

