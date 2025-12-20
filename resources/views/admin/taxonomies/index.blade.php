@extends('layouts.admin')

@push('head')
  <style>
    .adm-toolbar{ display:flex; gap:8px; align-items:flex-end; flex-wrap:wrap; margin:8px 0 10px; }
    .adm-toolbar .input, .adm-toolbar select{ padding:8px 10px; border:1px solid var(--border); border-radius:10px; background: var(--card); color: var(--fg); min-width: 200px; }
    .adm-toolbar .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .adm-toolbar .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }

    table.adm-table{ width:100%; border-collapse:collapse; background: var(--surface, var(--card)); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
    table.adm-table th, table.adm-table td{ padding:12px; border-bottom:1px solid var(--border); vertical-align:top; }
    table.adm-table thead th{ background: rgba(141,31,45,.16); text-align:left; }
    .row-actions{ display:flex; gap:6px; flex-wrap:wrap; }
    .btn.sm{ padding:6px 10px; border-radius:8px; font-size:13px; border:1px solid var(--accent); background: var(--accent); color:#fff; }
    .btn.danger{ background:#ef4444; border-color:#ef4444; }
    .btn.danger:hover{ background:#f87171; border-color:#f87171; }
    h2{ margin:0 0 8px; }
    .muted{ color: var(--muted); }
  </style>
@endpush

@section('content')
  <h1>분류 관리 (분야/장르/태그)</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  @php $isEmpty = ($disciplines->isEmpty() && $genres->isEmpty() && $tags->isEmpty()); @endphp
  @if($isEmpty)
    <div class="alert alert-warning">분야/장르/태그가 비어 있습니다. 아래 버튼으로 기본 분류를 채울 수 있습니다.</div>
    <form method="post" action="{{ route('admin.taxonomies.seed') }}" class="adm-toolbar">
      @csrf
      <button class="btn" type="submit">기본 분류 채우기 (분야·장르 + 태그 50여개)</button>
    </form>
  @endif

  {{-- 분야 --}}
  <section>
    <h2>분야(Discipline)</h2>
    <form method="post" action="{{ route('admin.taxonomies.store') }}" class="adm-toolbar">
      @csrf
      <input type="hidden" name="type" value="discipline">
      <input class="input" name="name" placeholder="이름" required>
      <input class="input" name="slug" placeholder="music, mc, dance" required>
      <button class="btn" type="submit">추가</button>
    </form>
    <table class="adm-table">
      <thead>
        <tr>
          <th style="width:80px">ID</th>
          <th>이름/슬러그</th>
          <th style="width:140px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($disciplines as $d)
          <tr>
            <td>{{ $d->id }}</td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'discipline','id'=>$d->id]) }}" class="adm-toolbar" style="margin:0">
                @csrf @method('put')
                <input class="input" name="name" value="{{ $d->name }}" required>
                <input class="input" name="slug" value="{{ $d->slug }}" required>
                <button class="btn sm" type="submit">저장</button>
              </form>
            </td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'discipline','id'=>$d->id]) }}" onsubmit="return confirm('삭제할까요?')">
                @csrf @method('delete')
                <button class="btn sm danger">삭제</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>

  {{-- 장르 --}}
  <section style="margin-top:16px">
    <h2>장르(Genre)</h2>
    <form method="post" action="{{ route('admin.taxonomies.store') }}" class="adm-toolbar">
      @csrf
      <input type="hidden" name="type" value="genre">
      <input class="input" name="name" placeholder="이름" required>
      <input class="input" name="slug" placeholder="kpop, hiphop, ..." required>
      <select name="discipline_id" class="input">
        <option value="">(선택)</option>
        @foreach($disciplines as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
      </select>
      <button class="btn" type="submit">추가</button>
    </form>
    <table class="adm-table">
      <thead>
        <tr>
          <th style="width:80px">ID</th>
          <th>이름/슬러그/분야</th>
          <th style="width:140px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($genres as $g)
          <tr>
            <td>{{ $g->id }}</td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'genre','id'=>$g->id]) }}" class="adm-toolbar" style="margin:0">
                @csrf @method('put')
                <input class="input" name="name" value="{{ $g->name }}" required>
                <input class="input" name="slug" value="{{ $g->slug }}" required>
                <select name="discipline_id" class="input">
                  <option value="">(선택)</option>
                  @foreach($disciplines as $d)<option value="{{ $d->id }}" @selected($g->discipline_id==$d->id)>{{ $d->name }}</option>@endforeach
                </select>
                <button class="btn sm" type="submit">저장</button>
              </form>
            </td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'genre','id'=>$g->id]) }}" onsubmit="return confirm('삭제할까요?')">
                @csrf @method('delete')
                <button class="btn sm danger">삭제</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>

  {{-- 태그 --}}
  <section style="margin-top:16px">
    <h2>태그(Tag)</h2>
    <form method="post" action="{{ route('admin.taxonomies.store') }}" class="adm-toolbar">
      @csrf
      <input type="hidden" name="type" value="tag">
      <input class="input" name="name" placeholder="이름" required>
      <input class="input" name="slug" placeholder="tv-popular, viral, ..." required>
      <button class="btn" type="submit">추가</button>
    </form>
    <form method="post" action="{{ route('admin.taxonomies.seed') }}" class="adm-toolbar" style="margin-top:-6px">
      @csrf
      <button class="btn ghost" type="submit" title="Spotify 스타일의 추천 태그를 일괄 추가">추천 태그 채우기</button>
      <span class="muted">중복은 자동으로 건너뜁니다.</span>
    </form>
    <table class="adm-table">
      <thead>
        <tr>
          <th style="width:80px">ID</th>
          <th>이름/슬러그</th>
          <th style="width:140px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($tags as $t)
          <tr>
            <td>{{ $t->id }}</td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'tag','id'=>$t->id]) }}" class="adm-toolbar" style="margin:0">
                @csrf @method('put')
                <input class="input" name="name" value="{{ $t->name }}" required>
                <input class="input" name="slug" value="{{ $t->slug }}" required>
                <button class="btn sm" type="submit">저장</button>
              </form>
            </td>
            <td>
              <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'tag','id'=>$t->id]) }}" onsubmit="return confirm('삭제할까요?')">
                @csrf @method('delete')
                <button class="btn sm danger">삭제</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>
@endsection
