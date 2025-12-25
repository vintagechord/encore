@extends('layouts.admin')

@push('head')
  <style>
    .adm-card{ background: var(--card); border:1px solid var(--border); border-radius:12px; padding:16px; }
    .adm-toolbar{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .input{ padding:8px 10px; border:1px solid var(--border); border-radius:10px; background: var(--card); color: var(--fg); }
    .btn{ padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; }
    .btn.ghost{ background:transparent; color:var(--fg); border-color: var(--border); }
    .muted{ color: var(--muted); }
    code{ background: var(--card-alt); border:1px solid var(--border); padding:2px 6px; border-radius:6px; }
    textarea{ width:100%; min-height:140px; background:var(--card); color:var(--fg); border:1px solid var(--border); border-radius:8px; padding:8px; }
  </style>
@endpush

@section('content')
  <h1>아티스트 대량 업로드(CSV)</h1>

  @if($errors->any())
    <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <div class="adm-card" style="margin-bottom:12px">
    <form method="post" action="{{ route('admin.artists.import') }}" enctype="multipart/form-data" class="adm-toolbar">
      @csrf
      <input class="input" type="file" name="file" accept=".csv,text/csv" required>
      <label class="input" style="display:inline-flex; gap:6px; align-items:center;">
        <input type="checkbox" name="replace" value="1"> 기존 삭제 후 교체
      </label>
      <button class="btn" type="submit">업로드</button>
      <a class="btn ghost" href="{{ route('admin.artists.index') }}">목록으로</a>
    </form>
  </div>

  <div class="adm-card">
    <div class="muted">헤더 예시: <code>stage_name,discipline,genres,tags,fee_min,fee_max,unit,fame_score,is_active,notes</code></div>
    <div class="muted" style="margin-top:6px">discipline: music | mc | dance | speaker (한글도 허용)</div>
    <div class="muted">genres/tags: 쉼표(,) 또는 파이프(|)로 구분합니다. 장르는 분야별로 자동 생성됩니다.</div>
    <div class="muted" style="margin-top:6px">예시 CSV</div>
    <textarea readonly>
stage_name,discipline,genres,tags,fee_min,fee_max,unit,fame_score,is_active,notes
아이유,music,ballad|pop,,20000000,40000000,appearance,95,1,샘플 메모
유재석,mc,announcer,방송|예능,5000000,15000000,appearance,98,1,
JUST JERK,dance,kpop|hiphop,댄스,3000000,7000000,appearance,90,1,
김미경,speaker,talk|leadership,강연,3000000,8000000,appearance,90,1,
    </textarea>
  </div>
@endsection

