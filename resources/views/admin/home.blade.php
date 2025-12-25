@extends('layouts.admin')
@push('head')
  <style>
    .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:16px; }
    .grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; }
    .stat{ display:flex; flex-direction:column; gap:6px; }
    .stat .n{ font-weight:800; font-size:22px; }
    .muted{ color:var(--muted); }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid var(--accent); background:var(--accent); color:#fff; text-decoration:none; }
  </style>
@endpush

@section('content')
    <div class="card" style="margin-bottom:12px">
      <h1 style="margin:0 0 6px">관리자 대시보드</h1>
      <p class="muted" style="margin:0">좌측 메뉴에서 관리 항목을 선택하세요.</p>
    </div>
    <section class="grid">
      <article class="card stat">
        <div class="muted">문의 수</div>
        <div class="n">{{ number_format($stats['intakes']) }}</div>
        <a class="btn" href="{{ route('admin.intakes') }}">문의 관리</a>
      </article>
      <article class="card stat">
        <div class="muted">추천셋</div>
        <div class="n">{{ number_format($stats['recommendation_sets']) }}</div>
        <a class="btn" href="{{ route('admin.intakes') }}">추천안 관리</a>
      </article>
      <article class="card stat">
        <div class="muted">아티스트</div>
        <div class="n">{{ number_format($stats['artists']) }}</div>
        <a class="btn" href="{{ route('admin.artists.index') }}">아티스트 관리</a>
      </article>
      <article class="card stat">
        <div class="muted">결제</div>
        <div class="n">{{ number_format($stats['payments']) }}</div>
        <a class="btn" href="{{ route('admin.intakes') }}">결제 관련</a>
      </article>
    </section>
@endsection
