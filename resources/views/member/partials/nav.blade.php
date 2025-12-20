<style>
  .member-nav { border-bottom:1px solid var(--border); background: transparent; margin-bottom: 16px; }
  .member-nav .wrap { max-width:1120px; margin:0 auto; padding:14px 20px; display:flex; gap:8px; flex-wrap:wrap; }
  .mitem { display:inline-flex; align-items:center; gap:8px; height:36px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); text-decoration:none; font-weight:600; transition: background .2s ease, color .2s ease, border-color .2s ease; }
  .mitem:hover { border-color: var(--btn-hover); color: var(--fg); background: rgba(141,31,45,.12); }
  /* Active tab: filled brand color for clear current-page indication */
  .mitem[aria-current="page"] { background: var(--btn); border-color: var(--btn); color: var(--btn-text); }
</style>
<nav class="member-nav" aria-label="회원 메뉴">
  <div class="wrap">
    <a class="mitem" href="{{ route('member.dashboard', ['type'=>'instant']) }}" @if(request()->routeIs('member.dashboard')) aria-current="page" @endif>추천셋</a>
    <a class="mitem" href="{{ route('member.inquiries', ['type'=>'instant']) }}" @if(request()->routeIs('member.inquiries')) aria-current="page" @endif>의뢰내역</a>
    <a class="mitem" href="{{ route('member.payments') }}" @if(request()->routeIs('member.payments')) aria-current="page" @endif>결제 내역</a>
    <a class="mitem" href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.edit')) aria-current="page" @endif>계정 설정</a>
  </div>
</nav>
