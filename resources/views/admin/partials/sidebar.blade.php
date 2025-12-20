<style>
  /* Global admin page wrapper: gives consistent left/right padding */
  .adm-wrap { padding: 0 24px; }
  @media (max-width: 860px){ .adm-wrap { padding: 0 18px; } }

  .adm-shell { display:grid; grid-template-columns: 240px 1fr; min-height: calc(100vh - 60px); }
  .adm-side { background: var(--card); border-right:1px solid var(--border); }
  .adm-side .logo { display:flex; align-items:center; gap:10px; padding:16px; font-weight:800; }
  .adm-menu { display:flex; flex-direction:column; padding:8px; gap:4px; }
  .adm-menu a { display:flex; align-items:center; gap:8px; padding:10px 12px; border-radius:10px; color:var(--fg); text-decoration:none; border:1px solid transparent; }
  .adm-menu a:hover { background: var(--card-alt); border-color: var(--border); }
  .adm-menu a[aria-current="page"] { background: var(--chip); border-color: var(--chip-border); }
  .adm-main { padding:18px; }
  @media (max-width: 860px){ .adm-shell { grid-template-columns: 1fr; } .adm-side { position: sticky; top: 0; z-index: 15; } }

  /* Unified admin buttons */
  .btn{ display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:8px 12px; border-radius:10px; border:1px solid var(--accent); background: var(--accent); color:#fff; text-decoration:none; font-weight:600; line-height:1; cursor:pointer; }
  .btn:hover{ background: var(--accent-hover); border-color: var(--accent-hover); color:#fff; text-decoration:none; }
  .btn.sm, .btn.btn-sm{ padding:6px 10px; border-radius:8px; font-size:13px; }
  .btn.ghost, .btn.btn-ghost{ background:transparent; color:var(--fg); border-color: var(--border); }
  .btn-primary{ background: var(--accent); border-color: var(--accent); color:#fff; }
  .btn-primary:hover{ background: var(--accent-hover); border-color: var(--accent-hover); }
  .btn.danger, .btn.btn-danger{ background:#ef4444; border-color:#ef4444; color:#fff; }
  .btn.danger:hover, .btn.btn-danger:hover{ background:#f87171; border-color:#f87171; }
  .row-actions{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
</style>

<div class="adm-wrap">
<div class="adm-shell">
  <aside class="adm-side">
    <div class="logo">
      <span aria-hidden="true" style="display:inline-flex;width:16px;height:16px;border-radius:6px;background:linear-gradient(135deg,#8d1f2d,#f3c652);"></span>
      <span>Encore Admin</span>
    </div>
    <nav class="adm-menu" aria-label="관리자 메뉴">
      <a href="{{ route('admin.home') }}" @if(request()->routeIs('admin.home')) aria-current="page" @endif>대시보드</a>
      <a href="{{ route('admin.intakes') }}" @if(request()->routeIs('admin.intakes')) aria-current="page" @endif>문의 관리</a>
      <a href="{{ route('admin.artists.index') }}" @if(request()->routeIs('admin.artists.*')) aria-current="page" @endif>아티스트 관리</a>
      <a href="{{ route('admin.taxonomies.index') }}" @if(request()->routeIs('admin.taxonomies.*')) aria-current="page" @endif>분류(분야/장르/태그)</a>
      <a href="{{ route('admin.success-stories.index') }}" @if(request()->routeIs('admin.success-stories.*')) aria-current="page" @endif>섭외 성공사례</a>
      <a href="{{ route('admin.testimonials.index') }}" @if(request()->routeIs('admin.testimonials.*')) aria-current="page" @endif>샘플 문의/후기</a>
      <a href="{{ route('admin.banners.index') }}" @if(request()->routeIs('admin.banners.*')) aria-current="page" @endif>배너 관리</a>
      <a href="{{ route('admin.notices.index') }}" @if(request()->routeIs('admin.notices.*')) aria-current="page" @endif>공지사항</a>
    </nav>
  </aside>
  <main class="adm-main">
    {!! $slot !!}
  </main>
</div>
</div>
