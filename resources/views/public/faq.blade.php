<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>FAQ | Encore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <style>
    body{ margin:0; background:var(--bg); color:var(--fg); font-family:-apple-system,system-ui,Segoe UI,Roboto,Helvetica,Arial,Apple SD Gothic Neo,Malgun Gothic,sans-serif; }
    .enc-container{ max-width:920px; margin:0 auto; padding:24px 20px; }
    h1{ margin:0 0 12px; font-size:22px; }
    .qa{ display:grid; gap:10px; }
    details{ background:var(--card); border:1px solid var(--border); border-radius:12px; padding:10px 12px; }
    summary{ cursor:pointer; font-weight:700; }
    summary::marker{ content:''; }
    summary::before{ content:'\25B6'; display:inline-block; margin-right:8px; transform:translateY(-1px); transition: transform .2s ease; }
    details[open] summary::before{ transform: rotate(90deg) translateX(-1px); }
    .muted{ color:var(--muted); }
    .gnav{ display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
    .gbtn{ display:inline-flex; align-items:center; height:34px; padding:0 12px; border-radius:999px; border:1px solid var(--border); background: var(--card); color: var(--fg); text-decoration:none; font-weight:600; }
    .gbtn:hover{ background: var(--card-alt); border-color: var(--chip-border); }
    .ghead{ margin:14px 0 8px; font-size:18px; font-weight:800; }
  </style>
  <script>
    // Smooth scroll for in-page anchors
    document.addEventListener('DOMContentLoaded', function(){
      document.querySelectorAll('.gbtn[href^="#"]').forEach(function(a){
        a.addEventListener('click', function(e){
          const id = a.getAttribute('href');
          const el = document.querySelector(id);
          if (!el) return;
          e.preventDefault();
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      });
    });
  </script>
  </head>
<body>
  {{-- FAQ에서는 마이페이지 상단 탭을 숨깁니다. --}}
  @include('public.partials.header', ['hideMemberNav' => true])
  <main class="enc-container">
    <h1>FAQ</h1>
    <nav class="gnav" aria-label="FAQ 그룹">
      <a class="gbtn" href="#g-encore">Encore</a>
      <a class="gbtn" href="#g-reco">추천셋·견적</a>
      <a class="gbtn" href="#g-inquiry">의뢰·일정</a>
      <a class="gbtn" href="#g-pay">결제</a>
      <a class="gbtn" href="#g-ops">운영·정책</a>
    </nav>
    <div class="qa">
      <div id="g-encore" class="ghead">Encore</div>
      <details>
        <summary>1초 Set · 1일 Set · 아티스트 맞춤형의 차이는 무엇인가요?</summary>
        <div class="muted" style="margin-top:8px; line-height:1.7">
          1) 1초 Set: 예산과 행사 정보를 입력하면 즉시 3가지 추천셋을 생성합니다.<br>
          2) 1일 Set: 요구사항을 함께 접수하면 관리자가 검토 후 1영업일 내 추천셋을 만들어 드립니다.<br>
          3) 아티스트 맞춤형: 특정 아티스트 지정을 기반으로 섭외 가능 여부와 견적을 안내합니다.
        </div>
      </details>
      <details>
        <summary>추천 결과는 어디서 확인하나요?</summary>
        <div class="muted" style="margin-top:8px">공유 링크 또는 로그인 후 내 페이지 &gt; 추천셋에서 확인할 수 있습니다.</div>
      </details>
      <details>
        <summary>추천셋이 마음에 들지 않으면 어떻게 하나요?</summary>
        <div class="muted" style="margin-top:8px">옵션 재생성 기능으로 새로운 조합을 받아보거나, 요구사항을 구체화해 1일 Set로 요청하실 수 있습니다.</div>
      </details>
      <details>
        <summary>연락은 어디로 하면 되나요?</summary>
        <div class="muted" style="margin-top:8px">내 페이지에서 해당 문의의 담당자에게 메시지를 남기거나, 하단 안내 메일로 문의해 주세요.</div>
      </details>

      <div id="g-reco" class="ghead">추천셋·견적</div>
      <details>
        <summary>견적은 어떻게 산정되나요?</summary>
        <div class="muted" style="margin-top:8px; line-height:1.7">
          - 문의에서 입력하신 총예산(최대 예산 중심)과 구성(장르/인원/시간)을 기반으로 조합합니다.<br>
          - 아티스트별 예상비(최소~최대)에 대관/음향 등 부대비는 포함되지 않을 수 있습니다.<br>
          - 최종 확정가는 아티스트/일정/기술요건 협의 후 결정됩니다.
        </div>
      </details>
      <details>
        <summary>1일 Set은 언제 받아볼 수 있나요?</summary>
        <div class="muted" style="margin-top:8px">보통 영업일 기준 1일 내 추천셋을 만들어 드리며, 지연 시 안내드립니다.</div>
      </details>
      <details>
        <summary>예산이 작아도 의뢰할 수 있나요?</summary>
        <div class="muted" style="margin-top:8px">가능합니다. 예산 범위에서 실현 가능한 구성을 제안하며, 규모 축소·구성 변경으로 대안을 드릴 수 있습니다.</div>
      </details>

      <div id="g-inquiry" class="ghead">의뢰·일정</div>
      <details>
        <summary>아티스트 섭외 가능 여부는 어떻게 확인하나요?</summary>
        <div class="muted" style="margin-top:8px">요청 접수 후 관리자 검토를 통해 스케줄과 조건을 확인하며, 가능 여부와 예상 비용을 안내드립니다.</div>
      </details>
      <details>
        <summary>일정이 바뀌거나 행사 정보가 변경되면 어떻게 하나요?</summary>
        <div class="muted" style="margin-top:8px">내 페이지에서 담당자에게 변경 요청을 남겨주세요. 일정/조건 변경은 비용과 섭외 가능 여부에 영향을 줄 수 있습니다.</div>
      </details>

      <div id="g-pay" class="ghead">결제</div>
      <details>
        <summary>결제는 언제/어떻게 진행되나요?</summary>
        <div class="muted" style="margin-top:8px">관리자가 섭외 가능 여부를 확인해 “결제하기” 상태로 변경하면, 내 페이지의 의뢰내역에서 무통장/카드로 결제할 수 있습니다.</div>
      </details>
      <details>
        <summary>세금계산서/영수증 발급이 가능한가요?</summary>
        <div class="muted" style="margin-top:8px">사업자 정보 제출 시 세금계산서 발행이 가능하며, 신용카드 결제 시 매출전표를 제공합니다.</div>
      </details>
      <details>
        <summary>취소/환불 규정은 어떻게 되나요?</summary>
        <div class="muted" style="margin-top:8px">아티스트 섭외 확정 후 취소 시 위약금이 발생할 수 있습니다. 확정 단계/일정 임박도에 따라 상이하며, 계약서에 고지됩니다.</div>
      </details>

      <div id="g-ops" class="ghead">운영·정책</div>
      <details>
        <summary>여행/숙박/장비(음향·조명) 비용은 포함인가요?</summary>
        <div class="muted" style="margin-top:8px">기본 견적에는 불포함인 경우가 많습니다. 지역/시간/장비 스펙에 따라 별도 비용이 산정됩니다.</div>
      </details>
      <details>
        <summary>무대/리허설/세트리스트 같은 운영 정보는 언제 확정하나요?</summary>
        <div class="muted" style="margin-top:8px">결제 후 섭외 확정 단계에서 담당자와 세부 운영안(리허설, 입·퇴장, 세트리스트)을 협의합니다.</div>
      </details>
      <details>
        <summary>영상 촬영이나 라이브 스트리밍, 2차 활용이 가능한가요?</summary>
        <div class="muted" style="margin-top:8px">아티스트/소속사 정책에 따라 별도 허가·비용이 필요할 수 있습니다. 사전에 용도를 알려주시면 가능 여부를 안내드립니다.</div>
      </details>
      <details>
        <summary>공공기관/학교/기업 행사도 가능한가요?</summary>
        <div class="muted" style="margin-top:8px">가능합니다. 필요 시 사업자등록증, 통장사본, 견적서/계약서/세금계산서 등 행정 서류를 지원합니다.</div>
      </details>
    </div>
  </main>
  @include('public.partials.footer')
</body>
</html>
