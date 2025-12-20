{{-- Shared public footer (refined for readability) --}}
<style id="enc-shared-footer-styles">
  .enc-footer { border-top:1px solid var(--border); background: transparent; margin-top: 24px; }
  /* Mobile: brand → nav → biz → copy (회사정보를 탭 아래 배치) */
  .enc-footer-inner { max-width:1120px; margin:0 auto; padding:22px 20px; display:grid; gap:8px; grid-template-columns: 1fr; grid-template-areas: 'brand' 'nav' 'biz' 'copy'; }
  .enc-footer .brand { display:flex; align-items:center; gap:10px; font-weight:800; color: var(--fg); text-decoration:none; grid-area: brand; align-self:start; }
  .enc-footer .brand-logo { height: 18px; width: auto; display:block; }
  .enc-footer .navlinks { display:flex; gap:22px; flex-wrap:wrap; align-items:flex-start; justify-content:flex-end; grid-area: nav; align-self:start; }
  .enc-footer a { color: var(--muted); text-decoration:none; }
  .enc-footer a:hover { color: var(--fg); text-decoration:underline; }
  .enc-footer .biz { color: var(--muted); font-size:13px; line-height:1.9; text-align:right; grid-area: biz; align-self:start; }
  .enc-footer .copy { border-top:1px solid var(--border); padding-top:12px; margin-top:6px; color: var(--muted); font-size:12px; grid-area: copy; }
  @media (min-width: 820px) {
    /* Desktop: 좌측 로고, 우측 상단 탭, 그 아래 회사정보 */
    .enc-footer-inner { grid-template-columns: 1fr 1fr 1fr; grid-template-areas: 'brand . nav' 'brand . biz' 'copy copy copy'; }
    /* 데스크톱에서는 각 문장을 한 줄로 유지하여 라인별로 깔끔하게 정렬 */
    .enc-footer .biz { white-space: nowrap; }
  }
</style>

<footer class="enc-footer" role="contentinfo">
  <div class="enc-footer-inner">
    <a class="brand" href="{{ route('home') }}" aria-label="Encore 홈">
      <img class="brand-logo" src="{{ asset('image/encore-logo.svg') }}" alt="Encore">
    </a>
    <nav class="navlinks" aria-label="바로가기">
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('faq') }}">FAQ</a>
      <a href="{{ route('notices.index') }}">공지사항</a>
    </nav>
    <div class="biz">
      (주)빈티지하우스 대표 정준영 | 주소: 경기도 김포시 사우동 880 시그마프라자 7층 | 이메일: help@vhouse.co.kr<br>
      사업자등록번호: 748-88-01472 | 통신판매업신고번호: 2023-경기김포-1524
    </div>
    <div class="copy">&copy; 2025 VintageHouse, Inc., All Rights Reserved.</div>
  </div>
</footer>
