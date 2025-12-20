@extends('layouts.admin')

@push('head')
  <style>
    .adm-toolbar{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:8px 0 10px; }
    .adm-toolbar .input, .adm-toolbar select{ padding:8px 10px; border:1px solid var(--border); border-radius:10px; background: var(--card); color: var(--fg); }
    .adm-toolbar .input::placeholder{ color: var(--muted); }
    .adm-toolbar .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .adm-toolbar .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }

    table.adm-table{ width:100%; border-collapse:collapse; background: var(--surface, var(--card)); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
    table.adm-table th, table.adm-table td{ padding:12px; border-bottom:1px solid var(--border); vertical-align:top; }
    table.adm-table thead th{ background: rgba(141,31,45,.16); text-align:left; }
    .chips{ display:flex; gap:6px; flex-wrap:wrap; }
    .chip{ display:inline-flex; align-items:center; padding:2px 8px; border-radius:999px; font-size:12px; border:1px solid var(--border); background: var(--card-alt); color: var(--muted); }
    .row-actions{ display:flex; gap:6px; flex-wrap:wrap; }
    .btn.sm{ padding:6px 10px; border-radius:8px; font-size:13px; }
    .btn.danger{ background:#ef4444; border-color:#ef4444; }
    .btn.danger:hover{ background:#f87171; border-color:#f87171; }
  </style>
@endpush

@section('content')
  <h1>아티스트 관리</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <form method="get" class="adm-toolbar">
    <input class="input" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="이름 검색">
    <select name="discipline_id" class="input">
        <option value="">분야 전체</option>
        @foreach($disciplines as $d)
          <option value="{{ $d->id }}" @selected(($filters['discipline_id'] ?? null)==$d->id)>{{ $d->name }}</option>
        @endforeach
    </select>
    <input class="input" type="number" name="min_fame" value="{{ $filters['min_fame'] ?? '' }}" placeholder="최소 유명세(0-100)" min="0" max="100">
    <input class="input" type="number" name="max_fame" value="{{ $filters['max_fame'] ?? '' }}" placeholder="최대 유명세(0-100)" min="0" max="100">

    @php $genresUnique = $genres->unique('name'); @endphp
    <select name="genre_ids[]" multiple size="3" class="input" title="장르">
      @foreach($genresUnique as $g)
        <option value="{{ $g->id }}" @selected(in_array($g->id, (array)($filters['genre_ids'] ?? [])))>{{ $g->name }}</option>
      @endforeach
    </select>

    <select name="tag_ids[]" multiple size="3" class="input" title="태그">
      @foreach($tags as $t)
        <option value="{{ $t->id }}" @selected(in_array($t->id, (array)($filters['tag_ids'] ?? [])))>{{ $t->name }}</option>
      @endforeach
    </select>

    <label class="chip"><input type="checkbox" name="is_active" value="1" @checked(($filters['is_active'] ?? null)===true)> 사용중만</label>
    <select name="per" class="input" title="표시 수">
      @php $perSel = (int)($filters['per'] ?? 20); @endphp
      @foreach([20,50,100] as $n)
        <option value="{{ $n }}" @selected($perSel===$n)>페이지당 {{ $n }}</option>
      @endforeach
    </select>
    <button class="btn" type="submit">필터</button>
    <a class="btn ghost" href="{{ route('admin.artists.index') }}">초기화</a>
    <a class="btn" href="{{ route('admin.artists.create') }}">새 아티스트</a>
  </form>

  <div class="adm-toolbar">
    <form method="post" action="{{ route('admin.artists.seed') }}">
      @csrf
      <input class="input" type="number" name="per" value="100" min="1" max="500" style="width:90px" title="분야별 목표 수">
      <button class="btn" type="submit">샘플 생성(분야별)</button>
    </form>
    <form method="post" action="{{ route('admin.artists.seed_real') }}" onsubmit="return confirm('기존 아티스트를 모두 교체하고 실존 인물/팀 20명씩 배치합니다. 진행할까요?')">
      @csrf
      <input type="hidden" name="replace" value="1">
      <button class="btn" type="submit">실존 20명/분야로 교체</button>
    </form>
    <a class="btn ghost" href="{{ route('admin.artists.import_form') }}">CSV 가져오기</a>
  </div>

  <table class="adm-table">
    <thead>
      <tr>
        <th>이름</th>
        <th>분야/장르</th>
        <th>태그</th>
        <th style="width:90px">유명세</th>
        <th>요율</th>
        <th style="width:160px">관리</th>
      </tr>
    </thead>
    <tbody>
      @forelse($artists as $a)
        <tr>
          <td>
            <strong>{{ $a->stage_name }}</strong>
            <div class="muted">{{ $a->legal_name }}</div>
          </td>
          <td>
            <div>{{ $a->discipline?->name ?? (is_string($a->discipline) ? $a->discipline : '') }}</div>
            <div class="muted chips" style="margin-top:4px">
              @php
                $rel = collect($a->genresRelation ?? []);
                if ($rel->count()) {
                  $gn = $rel->unique('id')->pluck('name')->all();
                } else {
                  $arr = collect(is_iterable($a->genres ?? null) ? $a->genres : (array)($a->genres ?? []));
                  $gn = $arr->map(fn($x)=> is_object($x)?($x->name ?? $x->label ?? ''):(string)$x)->filter()->unique()->values()->all();
                }
              @endphp
              @forelse($gn as $name)
                <span class="chip">{{ $name }}</span>
              @empty
                
              @endforelse
            </div>
          </td>
          <td>
            <div class="chips">
              @php $tags = is_iterable($a->tags ?? null) ? $a->tags : (array)($a->tags ?? []); @endphp
              @forelse($tags as $t)
                <span class="chip">#{{ is_object($t) ? ($t->name ?? $t->label ?? '') : $t }}</span>
              @empty
                
              @endforelse
            </div>
          </td>
          <td>{{ $a->fame_score }}</td>
          <td class="muted">
            @php $fee = $a->fees->first(); @endphp
            @if($fee)
              {{ $fee->currency }} {{ number_format($fee->min_fee) }} ~ {{ number_format($fee->max_fee) }} ({{ $fee->unit }})
            @else
              -
            @endif
          </td>
          <td>
            <div class="row-actions">
              <a class="btn sm" href="{{ route('admin.artists.edit',$a) }}">수정</a>
              <form method="post" action="{{ route('admin.artists.destroy',$a) }}" onsubmit="return confirm('삭제할까요?')">
                @csrf @method('delete')
                <button class="btn sm danger" type="submit">삭제</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted">결과 없음</td></tr>
      @endforelse
    </tbody>
  </table>

  <style>
    .enc-pager{ display:inline-flex; gap:6px; align-items:center; background:var(--card); border:1px solid var(--border); border-radius:999px; padding:6px; }
    .enc-pager a, .enc-pager span{ display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:30px; padding:0 10px; border-radius:999px; border:1px solid transparent; color:var(--fg); text-decoration:none; font-size:13px; }
    .enc-pager a:hover{ background:var(--card-alt); border-color: var(--chip-border); }
    .enc-pager .active{ background: var(--accent); color:#fff; border-color: var(--accent); }
    .enc-pager .disabled{ opacity:.45; cursor:not-allowed; }
  </style>
  <div style="margin-top:10px">{{ $artists->links('pagination::encore') }}</div>
@endsection
