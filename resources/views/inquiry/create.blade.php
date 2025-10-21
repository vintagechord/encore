<!doctype html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>문의하기</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root {
      --bg: #060913;
      --surface: #0f1729;
      --surface-alt: #152038;
      --card: #111b30;
      --border: #1e2a40;
      --border-alt: #273554;
      --text: #e5ecff;
      --muted: #94a3c4;
      --accent: #6366f1;
      --accent-hover: #818cf8;
      --danger-bg: rgba(248, 113, 113, 0.18);
      --danger-border: rgba(248, 113, 113, 0.45);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
      margin: 0;
      line-height: 1.5;
      background: var(--bg);
      color: var(--text);
      padding-top: 96px;
    }

    a {
      color: #8da2fb;
      text-decoration: none;
    }

    a:hover {
      color: #b3c0ff;
      text-decoration: underline;
    }

    h1 {
      margin: 0 0 12px;
      font-size: 22px;
    }

    .topbar {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .page {
      max-width: 960px;
      margin: 0 auto;
      padding: 0 24px 24px;
    }

    .site-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(7, 12, 24, 0.92);
      backdrop-filter: saturate(180%) blur(12px);
      border-bottom: 1px solid var(--border);
      z-index: 40;
    }

    .site-nav {
      max-width: 960px;
      margin: 0 auto;
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .site-nav .brand {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: var(--text);
    }

    .site-nav .logo-dot {
      width: 18px;
      height: 18px;
      border-radius: 6px;
      background: linear-gradient(135deg, #6366f1, #ec4899);
      display: inline-flex;
    }

    .site-nav .nav-link {
      color: var(--muted);
      border: 1px solid transparent;
      padding: 8px 12px;
      border-radius: 999px;
      transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .site-nav .nav-link:hover {
      background: rgba(99, 102, 241, 0.18);
      color: var(--text);
      border-color: rgba(99, 102, 241, 0.35);
    }

    fieldset {
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      margin: 12px 0;
      background: var(--surface);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
    }

    legend {
      padding: 0 8px;
      color: var(--muted);
    }

    .row {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      align-items: center;
    }

    label {
      display: block;
      font-weight: 600;
      margin: 8px 0 4px;
      color: var(--text);
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="number"],
    select {
      padding: 10px;
      border: 1px solid var(--border-alt);
      border-radius: 8px;
      min-width: 260px;
      background: var(--card);
      color: var(--text);
      transition: border-color .2s ease, box-shadow .2s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    input[type="number"]:focus,
    select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
      outline: none;
    }

    .qty {
      width: 78px;
      padding: 6px 8px;
      border-radius: 8px;
      border: 1px solid var(--border-alt);
      min-width: 0;
      background: var(--card);
      color: var(--text);
    }

    .chips {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .chip {
      display: inline-flex;
      gap: 10px;
      align-items: center;
      border: 1px solid var(--border-alt);
      border-radius: 999px;
      padding: 6px 10px;
      background: var(--surface-alt);
      flex-wrap: nowrap;
    }

    .chip span {
      white-space: nowrap;
      line-height: 1.2;
    }

    .chip .qty {
      flex: 0 0 80px;
      width: 80px;
      min-width: 80px;
    }

    .chip input[type="checkbox"] {
      flex: 0 0 auto;
      accent-color: var(--accent);
    }

    .muted {
      color: var(--muted);
    }

    .btn {
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid var(--accent);
      background: var(--accent);
      color: #fff;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      font-weight: 600;
      transition: background .2s ease, border-color .2s ease;
    }

    .btn:hover {
      background: var(--accent-hover);
      border-color: var(--accent-hover);
    }

    .btn.secondary {
      background: transparent;
      color: var(--accent);
    }

    .panel {
      padding: 10px 12px;
      border: 1px dashed var(--border-alt);
      border-radius: 10px;
      background: var(--card);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 8px;
    }

    .range-wrap {
      padding: 10px 12px;
      border: 1px solid var(--border);
      border-radius: 12px;
      background: var(--card);
    }

    .range-row {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .range-row input[type="range"] {
      width: 280px;
      accent-color: var(--accent);
    }

    /* 에러 블록 */
    .alert {
      border: 1px solid var(--danger-border);
      background: var(--danger-bg);
      color: #fda4af;
      padding: 12px;
      border-radius: 10px;
      margin: 12px 0;
    }

    .alert ul {
      margin: 6px 0 0 20px;
    }

    .site-footer {
      border-top: 1px solid var(--border);
      margin-top: 32px;
      padding: 18px 24px;
      text-align: center;
      color: var(--muted);
    }
  </style>
</head>

<body>
  <header class="site-header">
    <div class="site-nav">
      <a class="brand" href="{{ route('home') }}" aria-label="Encore 홈">
        <span class="logo-dot" aria-hidden="true"></span>Encore
      </a>
      <a class="nav-link" href="{{ route('inquiry.create') }}">문의</a>
    </div>
  </header>

  <main class="page">
    <!-- 상단: 제목 + 홈으로 -->
    <div class="topbar">
      <h1>문의하기</h1>
      <a class="btn" href="{{ route('home') }}">홈으로</a>
    </div>

  <!-- 에러 요약 -->
  @if ($errors->any())
  <div class="alert">
    <strong>입력값을 확인해 주세요.</strong>
    <ul>
      @foreach ($errors->all() as $msg)
      <li>{{ $msg }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form method="post" action="{{ route('inquiry.store') }}">
    @csrf

    <fieldset>
      <legend>연락처</legend>
      <div class="row">
        <div>
          <label for="contact_name">성함</label>
          <input id="contact_name" name="contact_name" type="text" required value="{{ old('contact_name') }}">
        </div>
        <div>
          <label for="contact_email">이메일</label>
          <input id="contact_email" name="contact_email" type="email" required value="{{ old('contact_email') }}">
        </div>
      </div>
    </fieldset>

    <fieldset>
      <legend>행사 일정</legend>
      <div class="row">
        <div>
          <label for="event_start">시작일</label>
          <input id="event_start" name="event_start" type="date" required value="{{ old('event_start') }}">
        </div>
        <div>
          <label for="event_end">종료일(선택)</label>
          <input id="event_end" name="event_end" type="date" value="{{ old('event_end') }}">
        </div>
      </div>
    </fieldset>

    <fieldset>
      <legend>분야 선택 (복수 선택 가능)</legend>
      @php $oldCats = (array) old('performance_categories', []); @endphp
      <div class="chips">
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="music" id="cat_music" {{ in_array('music',$oldCats)?'checked':'' }}> <span>음악</span>
        </label>
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="mc" id="cat_mc" {{ in_array('mc',$oldCats)?'checked':'' }}> <span>사회(MC)</span>
        </label>
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="dance" id="cat_dance" {{ in_array('dance',$oldCats)?'checked':'' }}> <span>댄스</span>
        </label>
      </div>

      <!-- 음악 장르 + 수량 -->
      <div id="panel_music" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 음악 장르와 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php
          $music = ['kpop'=>'K-POP','pop'=>'팝','rock'=>'록/메탈','indie'=>'인디','jazz'=>'재즈','hiphop'=>'힙합','rnb'=>'R&B','electronic'=>'일렉트로닉','classical'=>'클래식','folk'=>'포크','ballad'=>'발라드'];
          @endphp
          @foreach($music as $k=>$v)
          @php $mVal = (int) data_get(old('music_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-music" value="{{ $k }}" {{ $mVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-music" name="music_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $mVal ?: 0 }}" {{ $mVal>0?'':'disabled' }}>
            <span class="muted">팀</span>
          </label>
          @endforeach
        </div>
      </div>

      <!-- 사회(MC) 역할 + 수량 -->
      <div id="panel_mc" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">행사 진행 유형과 <strong>필요 인원</strong>을 입력해주세요.</div>
        <div class="grid">
          @php
          $mc = ['announcer'=>'아나운서/진행자','comedian'=>'개그맨/코미디언','recreation'=>'레크리에이션 강사'];
          @endphp
          @foreach($mc as $k=>$v)
          @php $cVal = (int) data_get(old('mc_role_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-mc" value="{{ $k }}" {{ $cVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-mc" name="mc_role_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $cVal ?: 0 }}" {{ $cVal>0?'':'disabled' }}>
            <span class="muted">명</span>
          </label>
          @endforeach
        </div>
      </div>

      <!-- 댄스 스타일 + 수량 -->
      <div id="panel_dance" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 댄스 스타일과 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php
          $dance = ['kpop'=>'K-POP 댄스','street'=>'스트릿(힙합/팝핑/락킹)','contemporary'=>'컨템포러리','ballet'=>'발레','traditional'=>'전통무용','cheer'=>'치어/퍼포먼스'];
          @endphp
          @foreach($dance as $k=>$v)
          @php $dVal = (int) data_get(old('dance_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-dance" value="{{ $k }}" {{ $dVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-dance" name="dance_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $dVal ?: 0 }}" {{ $dVal>0?'':'disabled' }}>
            <span class="muted">팀</span>
          </label>
          @endforeach
        </div>
      </div>
    </fieldset>

    <fieldset>
      <legend>예산 범위 (KRW)</legend>
      <div class="range-wrap">
        <div class="range-row">
          <span class="muted">최소</span>
          <input id="range_min" name="budget_min" type="range" min="0" max="300000000" step="100000" value="{{ (int)old('budget_min', 0) }}">
          <strong id="label_min">{{ number_format((int)old('budget_min', 0)) }}원</strong>
        </div>
        <div class="range-row" style="margin-top:8px">
          <span class="muted">최대</span>
          <input id="range_max" name="budget_max" type="range" min="0" max="300000000" step="100000" value="{{ (int)old('budget_max', 3000000) }}">
          <strong id="label_max">{{ number_format((int)old('budget_max', 3000000)) }}원</strong>
        </div>
        <div class="muted" style="margin-top:6px">드래그하여 범위를 설정하세요. (0 ~ 300,000,000 / 10만 원 단위)</div>
      </div>
    </fieldset>

    <div style="margin-top:14px; display:flex; gap:8px;">
      <a class="btn" href="{{ route('home') }}">홈으로</a>
      <button class="btn" type="submit">문의 보내기</button>
    </div>
  </form>
  </main>

  <script>
    (function() {
      // 패널 토글
      const catMusic = document.getElementById('cat_music');
      const catMc = document.getElementById('cat_mc');
      const catDance = document.getElementById('cat_dance');
      const panelMusic = document.getElementById('panel_music');
      const panelMc = document.getElementById('panel_mc');
      const panelDance = document.getElementById('panel_dance');

      function toggle(el, panel) {
        panel.style.display = (el && el.checked) ? '' : 'none';
        if (el && !el.checked) {
          panel.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
          panel.querySelectorAll('input[type="number"]').forEach(n => {
            n.value = 0;
            n.disabled = true;
          });
        }
      }

      [catMusic, catMc, catDance].forEach((el) => {
        el.addEventListener('change', () => {
          const p = (el === catMusic) ? panelMusic : (el === catMc ? panelMc : panelDance);
          toggle(el, p);
        });
        const p = (el === catMusic) ? panelMusic : (el === catMc ? panelMc : panelDance);
        toggle(el, p); // 초기 표시
      });

      // 체크박스 ↔ 수량 입력 연동
      function bindCheckWithQty(panel, checkboxSel, qtySel) {
        panel.querySelectorAll(checkboxSel).forEach((cb) => {
          const label = cb.closest('label');
          const qty = label.querySelector(qtySel);

          function syncOne() {
            if (cb.checked) {
              qty.disabled = false;
              if (!qty.value || Number(qty.value) === 0) qty.value = 1;
            } else {
              qty.value = 0;
              qty.disabled = true;
            }
          }
          cb.addEventListener('change', syncOne);
          syncOne(); // 초기 상태
        });
      }
      bindCheckWithQty(panelMusic, '.chk-music', '.qty-music');
      bindCheckWithQty(panelMc, '.chk-mc', '.qty-mc');
      bindCheckWithQty(panelDance, '.chk-dance', '.qty-dance');

      // 예산 원화 포맷
      const fmt = (n) => (n || 0).toLocaleString('ko-KR') + '원';
      const rmin = document.getElementById('range_min');
      const rmax = document.getElementById('range_max');
      const lmin = document.getElementById('label_min');
      const lmax = document.getElementById('label_max');

      function syncBudget(e) {
        let a = parseInt(rmin.value || '0', 10);
        let b = parseInt(rmax.value || '0', 10);
        if (a > b) {
          if (e && e.target === rmin) rmax.value = a;
          else rmin.value = b;
          a = parseInt(rmin.value, 10);
          b = parseInt(rmax.value, 10);
        }
        lmin.textContent = fmt(a);
        lmax.textContent = fmt(b);
      }
      rmin.addEventListener('input', syncBudget);
      rmax.addEventListener('input', syncBudget);
      syncBudget();
    })();
  </script>

  <footer class="site-footer">
    <span>&copy; {{ date('Y') }} Encore. 필요한 정보를 편하게 남겨주세요.</span>
  </footer>
</body>

</html>
