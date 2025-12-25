<style>
  .member-nav { position: sticky; top: 110px; z-index: 15; border-bottom:1px solid var(--border); background: rgba(10,10,10,0.85); backdrop-filter: blur(8px); margin-bottom: 16px; }
  [data-theme="light"] .member-nav { background: rgba(255,255,255,0.9); }
  .member-nav .wrap { max-width:1120px; margin:0 auto; padding:14px 20px; display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
  .mitem { display:inline-flex; align-items:center; justify-content:center; gap:8px; height:38px; min-width:120px; padding:0 14px; border-radius:10px; border:1px solid var(--border); background: var(--card); color: var(--fg); text-decoration:none; font-weight:600; transition: background .2s ease, color .2s ease, border-color .2s ease; }
  .mitem:hover { border-color: var(--btn-hover); color: var(--fg); background: rgba(243,198,82,.14); }
  /* Active tab: filled brand color for clear current-page indication */
  .mitem[aria-current="page"] { background: var(--btn); border-color: var(--btn); color: var(--btn-text); }
  @media (max-width: 768px) {
    .member-nav { top: 96px; }
    .mitem { min-width: 110px; height: 36px; padding: 0 10px; }
  }
</style>
<nav class="member-nav" aria-label="회원 메뉴">
  <div class="wrap">
    <a class="mitem" href="{{ route('member.dashboard', ['type'=>'instant']) }}" @if(request()->routeIs('member.dashboard')) aria-current="page" @endif>추천셋</a>
    <a class="mitem" href="{{ route('member.inquiries', ['type'=>'instant']) }}" @if(request()->routeIs('member.inquiries')) aria-current="page" @endif>의뢰내역</a>
    <a class="mitem" href="{{ route('member.favorites') }}" @if(request()->routeIs('member.favorites')) aria-current="page" @endif>나의 아티스트</a>
    <a class="mitem" href="{{ route('member.payments') }}" @if(request()->routeIs('member.payments')) aria-current="page" @endif>결제 내역</a>
    <a class="mitem" href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.edit')) aria-current="page" @endif>계정 설정</a>
  </div>
</nav>
