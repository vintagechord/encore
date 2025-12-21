<!doctype html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>문의하기</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="dark light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">

  <style>
    :root {
      --bg: radial-gradient(1200px 520px at 12% -8%, rgba(141, 31, 45, 0.35), rgba(11, 9, 10, 0) 60%),
        radial-gradient(900px 480px at 92% 2%, rgba(243, 198, 82, 0.22), rgba(11, 9, 10, 0) 55%),
        #0b090a;
      --surface: #151012;
      --surface-alt: #1b1316;
      --card: #1a1316;
      --border: #2a1c22;
      --border-alt: #352029;
      --text: #f7f1e9;
      --muted: #b6a89a;
      --accent: #f3c652;
      --accent-hover: #f0b840;
      --danger-bg: rgba(248, 113, 113, 0.18);
      --danger-border: rgba(248, 113, 113, 0.45);
      --font-sans: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
      --font-display: "Pretendard", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
    }

    [data-theme="light"] {
      --bg: radial-gradient(980px 360px at 10% -6%, rgba(141, 31, 45, 0.08), rgba(255, 247, 230, 0) 60%),
        linear-gradient(180deg, #fff7e6 0%, #f4e9d8 100%);
      --surface: #fffdf8;
      --surface-alt: #f6ecdd;
      --card: #fff9f0;
      --border: #e6d4c0;
      --border-alt: #d7c5b3;
      --text: #2b1b1b;
      --muted: #6b5b53;
      --accent: #e3b648;
      --accent-hover: #d5a63b;
      --danger-bg: rgba(248, 113, 113, 0.10);
      --danger-border: rgba(248, 113, 113, 0.35);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-sans);
      margin: 0;
      line-height: 1.5;
      background: var(--bg);
      color: var(--text);
      padding-top: 0;
    }

    a { color: inherit; text-decoration: none; }
    a:hover { color: var(--accent-hover); text-decoration: underline; }

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
      padding: 36px 24px 24px; /* 상단 여백 강화 */
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
      background: linear-gradient(135deg, #8d1f2d, #f3c652);
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
      background: rgba(141, 31, 45, 0.16);
      color: var(--text);
      border-color: rgba(141, 31, 45, 0.35);
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
      border-color: var(--btn);
      box-shadow: 0 0 0 2px rgba(141, 31, 45, 0.3);
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
      border: 1px solid var(--btn);
      background: var(--btn);
      color: var(--btn-text);
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      font-weight: 600;
      transition: background .2s ease, border-color .2s ease;
    }

    .btn:hover {
      background: var(--btn-hover);
      border-color: var(--btn-hover);
    }

    .btn.secondary {
      background: transparent;
      color: var(--fg);
      border-color: var(--border);
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
      padding: 14px;
      border: 1px solid var(--border);
      border-radius: 14px;
      background: var(--card);
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);
    }

    .range-row { display:flex; gap:12px; align-items:center; margin:8px 0; }
    .range-label { width:42px; color: var(--muted); font-weight:600; }
    .range-field { position:relative; flex:1; min-width:260px; padding: 0 16px; }
    .range { width:100%; height:10px; border-radius:999px; background: linear-gradient(to right, var(--accent) 0 var(--p,0%), rgba(148,163,184,.25) var(--p,0%)); outline:none; -webkit-appearance:none; appearance:none; }
    .range::-webkit-slider-thumb { -webkit-appearance:none; appearance:none; width:18px; height:18px; border-radius:50%; background: var(--accent); border: 2px solid #fff3; box-shadow: 0 2px 6px rgba(0,0,0,.25); cursor:pointer; }
    .range::-moz-range-thumb { width:18px; height:18px; border-radius:50%; background: var(--accent); border: 2px solid #fff3; box-shadow: 0 2px 6px rgba(0,0,0,.25); cursor:pointer; }
    .range-bubble { position:absolute; transform: translateX(-50%); background: var(--card-alt); color: var(--fg); border:1px solid var(--border-alt); padding:2px 8px; border-radius:8px; font-size:12px; white-space:nowrap; z-index: 2; pointer-events:none; }
    .range-bubble.bubble-top { top:-32px; }
    .range-bubble.bubble-bottom { bottom:-32px; }

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

    /* Date picker icon visibility */
    :root[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1) brightness(1.6); }
    :root[data-theme="dark"] input[type="date"] { color-scheme: dark; }
    :root[data-theme="light"] input[type="date"]::-webkit-calendar-picker-indicator { filter: none; }
  </style>
</head>

<body>
  @include('public.partials.header', ['hideMemberNav' => true, 'subTitle' => '문의하기'])

  <main class="page">

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

  <form id="inquiryForm" method="post" action="{{ route('inquiry.store') }}">
    @csrf

    <!-- 문의 유형 선택: 1초 Set / 1일 Set -->
    <input type="hidden" name="request_mode" id="request_mode" value="{{ $prefMode ?? old('request_mode','instant') }}">
    <style>
      .optgrid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:10px; margin-bottom:10px; }
      .optcard{ position:relative; display:flex; flex-direction:column; min-height:200px; border:1px solid var(--border); border-radius:14px; background:var(--card); padding:20px; cursor:pointer; transition: border-color .2s ease, box-shadow .2s ease, transform .08s ease; }
      .optcard:hover{ border-color: var(--border-alt); box-shadow: inset 0 0 0 2px rgba(141,31,45,.18); }
      .optcard.active{ border-color: var(--btn); box-shadow: inset 0 0 0 2px rgba(141,31,45,.35); }
      .optcard h3{ margin:0 0 8px; font-size: clamp(18px, 2.2vw, 22px); font-weight: 800; letter-spacing: -0.01em; }
      .optcard p{ margin:0 0 10px; color:var(--muted); font-size:14px; min-height:40px; }
      .optcard .btn{ margin-top:auto; border:1px solid var(--btn); background: var(--btn); color: var(--btn-text); }
      .optcard .btn[disabled]{ opacity:.6; cursor:not-allowed; }
      .optcard .btn:hover{ background: var(--btn-hover); border-color: var(--btn-hover); }
    </style>

    <div class="optgrid" role="tablist" aria-label="문의 유형">
      <div class="optcard {{ ($prefMode ?? old('request_mode','instant'))==='instant' ? 'active' : '' }}" id="cardInstant" data-mode="instant" role="tab" aria-selected="{{ ($prefMode ?? old('request_mode','instant'))==='instant' ? 'true':'false' }}">
        <h3>1초 Set</h3>
        <p>옵션 입력 즉시 3가지 추천안 자동 생성. 마음에 들지 않으면 재생성 가능.</p>
        <button class="btn" type="button" id="btnInstant" disabled>추천셋 즉시 생성</button>
      </div>
      <div class="optcard {{ ($prefMode ?? old('request_mode'))==='one_day' ? 'active' : '' }}" id="cardOneDay" data-mode="one_day" role="tab" aria-selected="{{ ($prefMode ?? old('request_mode'))==='one_day' ? 'true':'false' }}">
        <h3>1일 Set</h3>
        <p>요구사항을 작성해 보내주시면 관리자가 큐레이션한 3가지 셋을 1일 내 전달.</p>
        <button class="btn" type="button" id="btnOneDay">관리자의 추천셋</button>
      </div>
      <div class="optcard {{ ($prefMode ?? '')==='direct' ? 'active' : '' }}" id="cardDirect" data-mode="direct" role="tab" aria-selected="{{ ($prefMode ?? '')==='direct' ? 'true':'false' }}">
        <h3>아티스트 맞춤형</h3>
        <p>원하는 아티스트를 지정해 섭외 요청하기.</p>
        <button class="btn" type="button" id="btnDirect">아티스트 지정 섭외</button>
      </div>
    </div>

    <div id="stdForm">
    <fieldset>
      <legend>연락처</legend>
      <div class="row">
        <div>
          <label for="contact_name">성함</label>
          <input id="contact_name" name="contact_name" type="text" required value="{{ old('contact_name', auth()->user()->name ?? '') }}">
        </div>
        <div>
          <label for="contact_email">이메일</label>
          <input id="contact_email" name="contact_email" type="email" required value="{{ old('contact_email', auth()->user()->email ?? '') }}">
        </div>
        <div>
          <label for="contact_phone">전화번호(선택)</label>
          <input id="contact_phone" name="contact_phone" type="text" inputmode="tel" placeholder="예: 010-1234-5678" value="{{ old('contact_phone') }}">
        </div>
      </div>
    </fieldset>

    {{-- 특정 아티스트 직접요청은 별도 페이지 /request/artist 에서 받습니다. --}}

    <fieldset>
      <legend>행사 일정</legend>
      <div class="row">
        <div>
          <label for="event_start">시작일</label>
          <input id="event_start" name="event_start" type="date" value="{{ old('event_start') }}">
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
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="performance" id="cat_performance" {{ in_array('performance',$oldCats)?'checked':'' }}> <span>퍼포먼스</span>
        </label>
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="planned" id="cat_planned" {{ in_array('planned',$oldCats)?'checked':'' }}> <span>기획공연</span>
        </label>
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="celebrity" id="cat_celebrity" {{ in_array('celebrity',$oldCats)?'checked':'' }}> <span>셀럽</span>
        </label>
        <label class="chip">
          <input type="checkbox" name="performance_categories[]" value="foreign" id="cat_foreign" {{ in_array('foreign',$oldCats)?'checked':'' }}> <span>외국인</span>
        </label>
      </div>

      <!-- 음악 장르 + 수량 -->
      <div id="panel_music" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 음악 장르와 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php
          $music = [
            'kpop'=>'K-POP','pop'=>'팝','rock'=>'록/메탈','indie'=>'인디','jazz'=>'재즈','hiphop'=>'힙합','rnb'=>'R&B','electronic'=>'일렉트로닉','ballad'=>'발라드','folk'=>'포크',
            // 클래식(음악 하위)
            'orchestra'=>'오케스트라','soloist'=>'솔리스트','ensemble'=>'앙상블','vocal'=>'성악','opera'=>'오페라','choir'=>'합창단',
            // 전통(음악 하위)
            'gugak_orch'=>'국악관현악단','master'=>'명인/명창','fusion'=>'퓨전국악','dance_trad'=>'전통무용','yeonhui'=>'전통연희','pumba'=>'품바/마당극','minyo'=>'민요/판소리/전통음악'
          ];
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

      <!-- 퍼포먼스 세부 + 수량 -->
      <div id="panel_performance" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 퍼포먼스 유형과 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php
          $perf = [
            'percussive'=>'타악퍼포먼스','brass'=>'브라스 퍼포먼스','magic'=>'마술쇼',
            'mime_juggle_bubble_clown'=>'마임/저글링/버블/삐에로','drawing_sand'=>'드로잉쇼/샌드애니메이션',
            'martial'=>'무술 퍼포먼스','laser_led'=>'레이저/LED 퍼포먼스','brush'=>'붓글씨 퍼포먼스',
            'media'=>'미디어 퍼포먼스','robot'=>'로봇','caricature_face'=>'캐리커쳐/페이스페인팅',
            'number'=>'넘버벌 퍼포먼스','circus'=>'서커스','parade'=>'퍼레이드/마칭',
            'cocktail'=>'칵테일 쇼','fire'=>'불쇼'
          ];
          @endphp
          @foreach($perf as $k=>$v)
          @php $pVal = (int) data_get(old('performance_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-performance" value="{{ $k }}" {{ $pVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-performance" name="performance_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $pVal ?: 0 }}" {{ $pVal>0?'':'disabled' }}>
            <span class="muted">팀</span>
          </label>
          @endforeach
        </div>
      </div>


      <!-- 기획공연 세부 + 수량 -->
      <div id="panel_planned" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 기획공연 유형과 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php $planned = ['convergence'=>'융복합 공연','north_korea'=>'북한예술단','gag'=>'개그공연','foreign_troupe'=>'외국인 공연단','theatre'=>'극공연','kids'=>'어린이 공연','kids_singalong'=>'어린이 싱어롱쇼','etc'=>'기타']; @endphp
          @foreach($planned as $k=>$v)
          @php $plVal = (int) data_get(old('planned_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-planned" value="{{ $k }}" {{ $plVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-planned" name="planned_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $plVal ?: 0 }}" {{ $plVal>0?'':'disabled' }}>
            <span class="muted">팀</span>
          </label>
          @endforeach
        </div>
      </div>

      <!-- 셀럽 세부 + 수량 -->
      <div id="panel_celebrity" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">필요한 셀럽 유형과 <strong>필요 인원</strong>을 입력해주세요.</div>
        <div class="grid">
          @php $cele = ['mentor'=>'명사','expert'=>'전문강사','broadcaster'=>'방송인','professor'=>'교수','chef'=>'셰프','health'=>'헬스','model'=>'모델','beauty'=>'뷰티','business'=>'기업인','sports'=>'스포츠','religion'=>'종교인','creator'=>'크리에이터']; @endphp
          @foreach($cele as $k=>$v)
          @php $clVal = (int) data_get(old('celebrity_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-celebrity" value="{{ $k }}" {{ $clVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-celebrity" name="celebrity_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $clVal ?: 0 }}" {{ $clVal>0?'':'disabled' }}>
            <span class="muted">명</span>
          </label>
          @endforeach
        </div>
      </div>

      <!-- 외국인 세부 + 수량 -->
      <div id="panel_foreign" class="panel" style="display:none; margin-top:10px">
        <div class="muted" style="margin-bottom:6px">원하시는 국가/지역과 <strong>필요 팀 수</strong>를 입력해주세요.</div>
        <div class="grid">
          @php $foreign = ['china'=>'중국','japan'=>'일본','usa'=>'미국','se_asia'=>'동남아시아','etc'=>'기타공연']; @endphp
          @foreach($foreign as $k=>$v)
          @php $fVal = (int) data_get(old('foreign_counts', []), $k, 0); @endphp
          <label class="chip">
            <input type="checkbox" class="chk-foreign" value="{{ $k }}" {{ $fVal>0?'checked':'' }}>
            <span>{{ $v }}</span>
            <input type="number" class="qty qty-foreign" name="foreign_counts[{{ $k }}]" min="0" max="50" step="1" value="{{ $fVal ?: 0 }}" {{ $fVal>0?'':'disabled' }}>
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
          <span class="range-label">최소</span>
          <div class="range-field">
            <input class="range" id="range_min" name="budget_min" type="range" min="0" max="300000000" step="100000" value="{{ (int)old('budget_min', 0) }}">
            <output id="bubble_min" class="range-bubble bubble-top">{{ number_format((int)old('budget_min', 0)) }}원</output>
          </div>
        </div>
        <div class="range-row">
          <span class="range-label">최대</span>
          <div class="range-field">
            <input class="range" id="range_max" name="budget_max" type="range" min="0" max="300000000" step="100000" value="{{ (int)old('budget_max', 3000000) }}">
            <output id="bubble_max" class="range-bubble bubble-bottom">{{ number_format((int)old('budget_max', 3000000)) }}원</output>
          </div>
        </div>
      </div>
    </fieldset>

    <!-- one-day 전용 요구사항 -->
    <fieldset id="oneDayBox" style="display:none">
      <legend>추가 행사/공연 설명 및 요구사항(필수)</legend>
      <textarea name="requirements" id="requirements" rows="8" placeholder="행사 목적, 예상 관객, 원하는 분위기/장르, 제한사항 등을 자유롭게 적어주세요." style="width:100%; padding:12px; border-radius:10px; border:1px solid var(--border-alt); background:var(--card); color:var(--text)">{{ old('requirements') }}</textarea>
      <div class="muted" style="margin-top:6px">1일 Set을 선택하면 요구사항은 필수입니다.</div>
    </fieldset>
    </div>

    <!-- Direct(아티스트 맞춤형) 폼 -->
    <div id="directForm" style="display:none">
      <style>
        /* Direct(아티스트 맞춤형) 입력폼 정돈: 투명 입력, 균일 높이, 반응형 정렬 */
        #directForm .row { align-items: stretch; }
        #directForm fieldset > .row { gap: 12px; width: 100%; }
        #directForm fieldset > .row > div {
          flex: 1 1 260px; min-width:260px;
          display:flex; flex-direction:column; justify-content:flex-start;
          background: var(--card); border: 1px solid var(--border);
          border-radius: 12px; padding: 12px; overflow: hidden;
        }
        #directForm label { margin: 0 0 6px; }
        #directForm input[type="text"],
        #directForm input[type="email"],
        #directForm input[type="date"],
        #directForm input[type="number"],
        #directForm select {
          background: transparent !important; height: 48px; box-sizing: border-box;
          padding: 10px 12px; border:1px solid var(--border-alt); border-radius:8px; color: var(--text);
          width: 100%; min-width: 0; /* prevent overflow in nested rows */
        }
        #directForm textarea { width:100%; min-height: 200px; background: transparent !important; border:1px solid var(--border-alt); border-radius:10px; padding:12px; color: var(--text); }
        #directForm .row .row { align-items: center; gap: 10px; }
        #directForm .row .row > div { flex:1 1 0; min-width:0; }
        /* 모바일: 컬럼 스택 및 간격 보정 */
        @media (max-width: 640px){
          #directForm fieldset > .row > div { min-width: 100%; }
        }
      </style>
      <fieldset>
        <legend>연락처</legend>
        <div class="row">
          <div>
            <label for="d_contact_name">성함</label>
            <input id="d_contact_name" name="contact_name" type="text" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required>
          </div>
          <div>
            <label for="d_contact_email">이메일</label>
            <input id="d_contact_email" name="contact_email" type="email" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required>
          </div>
          <div>
            <label for="d_contact_phone">전화번호(선택)</label>
            <input id="d_contact_phone" name="contact_phone" type="text" inputmode="tel" value="{{ old('contact_phone') }}">
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend>단체 정보</legend>
        <div class="row">
          <div>
            <label>구분</label>
            <select id="df_org_type" name="organization_type" required>
              <option value="">(선택)</option>
              <option value="business">사업자(법인/개인)</option>
              <option value="public">공공기관/공기업</option>
              <option value="school">학교/교육기관</option>
              <option value="nonprofit">비영리단체</option>
              <option value="other">기타 단체</option>
            </select>
            <div class="muted">개인 문의는 본 채널에서 받지 않습니다.</div>
          </div>
          <div>
            <label>단체명/회사명</label>
            <input name="org_name" placeholder="예: 엔코르 주식회사">
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend>요청 내용</legend>
        <div class="row" aria-label="요청 기본 정보">
          <div>
            <label>원하는 아티스트</label>
            <input name="requested_artist_name" required placeholder="지정 아티스트명을 입력하세요">
          </div>
          <div>
            <label>행사 일정(선택)</label>
            <div class="row">
              <div><input name="event_start" type="date" placeholder="연도. 월. 일."></div>
              <div><input name="event_end" type="date" placeholder="연도. 월. 일."></div>
            </div>
          </div>
        </div>
        <div style="margin-top:12px">
          <label>추가 메모(선택)</label>
          <textarea name="notes" rows="8" placeholder="행사 목적, 규모, 현장 여건(실내/야외), 특이 사항 등을 자유롭게 남겨주세요."></textarea>
        </div>
        <label style="display:flex; gap:8px; align-items:center; margin-top:8px;">
          <input type="checkbox" name="intent_confirmed" value="1">
          <span class="muted">본 문의는 단체/법인/공공기관 소속이며, 실제 섭외 의사가 있습니다.</span>
        </label>
      </fieldset>
    </div>

    <div style="margin-top:14px; display:flex; gap:8px;">
      <a class="btn" href="{{ route('home') }}" style="background:transparent;color:var(--accent)">홈으로</a>
      <button id="submitBtn" class="btn" type="submit">추천셋 즉시 생성</button>
    </div>
  </form>
  </main>

  <script>
    (function() {
      // 패널 토글
      const catMusic = document.getElementById('cat_music');
      const catMc = document.getElementById('cat_mc');
      const catDance = document.getElementById('cat_dance');
      const catPerformance = document.getElementById('cat_performance');
      const catPlanned = document.getElementById('cat_planned');
      const catCelebrity = document.getElementById('cat_celebrity');
      const catForeign = document.getElementById('cat_foreign');

      const panelMusic = document.getElementById('panel_music');
      const panelMc = document.getElementById('panel_mc');
      const panelDance = document.getElementById('panel_dance');
      const panelPerformance = document.getElementById('panel_performance');
      const panelPlanned = document.getElementById('panel_planned');
      const panelCelebrity = document.getElementById('panel_celebrity');
      const panelForeign = document.getElementById('panel_foreign');

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

      function panelFor(el) {
        const key = (el && el.id) ? el.id.replace('cat_', 'panel_') : '';
        return key ? document.getElementById(key) : null;
      }

      const catList = [catMusic, catMc, catDance, catPerformance, catPlanned, catCelebrity, catForeign].filter(Boolean);
      catList.forEach((el) => {
        const p = panelFor(el);
        if (!p) return;
        el.addEventListener('change', () => toggle(el, p));
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
      bindCheckWithQty(panelPerformance, '.chk-performance', '.qty-performance');
      bindCheckWithQty(panelPlanned, '.chk-planned', '.qty-planned');
      bindCheckWithQty(panelCelebrity, '.chk-celebrity', '.qty-celebrity');
      bindCheckWithQty(panelForeign, '.chk-foreign', '.qty-foreign');

      // 예산 원화 포맷
      const fmt = (n) => (n || 0).toLocaleString('ko-KR') + '원';
      const rmin = document.getElementById('range_min');
      const rmax = document.getElementById('range_max');
      const bmin = document.getElementById('bubble_min');
      const bmax = document.getElementById('bubble_max');

      function pct(input){
        const min = parseInt(input.min||'0',10), max = parseInt(input.max||'100',10);
        const val = parseInt(input.value||'0',10);
        return Math.min(100, Math.max(0, ((val-min)/(max-min))*100));
      }

      function positionBubble(input, bubble){
        if (!bubble) return;
        const field = bubble.parentElement; // .range-field
        const rectW = field.clientWidth || 0;
        const half = (bubble.offsetWidth||40)/2;
        const padL = 0; // already padded in field container
        const p = pct(input)/100;
        let x = padL + p * rectW;
        const minX = half; const maxX = rectW - half;
        x = Math.min(maxX, Math.max(minX, x));
        bubble.style.left = x + 'px';
        // keep gradient fill for the track
        input.style.setProperty('--p', (p*100)+'%');
      }

      function syncBudget(e) {
        let a = parseInt(rmin.value || '0', 10);
        let b = parseInt(rmax.value || '0', 10);
        if (a > b) {
          if (e && e.target === rmin) rmax.value = a; else rmin.value = b;
          a = parseInt(rmin.value, 10); b = parseInt(rmax.value, 10);
        }
        if (bmin) { bmin.textContent = fmt(a); positionBubble(rmin, bmin); }
        if (bmax) { bmax.textContent = fmt(b); positionBubble(rmax, bmax); }
      }
      ['input','change'].forEach(ev=>{ rmin.addEventListener(ev, syncBudget); rmax.addEventListener(ev, syncBudget); });
      syncBudget();

      // 문의 유형 토글
      const modeInput = document.getElementById('request_mode');
      const form = document.getElementById('inquiryForm');
      const cards = [document.getElementById('cardInstant'), document.getElementById('cardOneDay'), document.getElementById('cardDirect')];
      const oneBox = document.getElementById('oneDayBox');
      const req = document.getElementById('requirements');
      const evtStart = document.getElementById('event_start');
      const submitBtn = document.getElementById('submitBtn');
      const stdForm = document.getElementById('stdForm');
      const directForm = document.getElementById('directForm');
      const btnInstant = document.getElementById('btnInstant');
      const btnOneDay = document.getElementById('btnOneDay');
      const btnDirect = document.getElementById('btnDirect');
      const directAction = '{{ route('direct.request.store') }}';
      const standardAction = '{{ route('inquiry.store') }}';
      function toggleSectionEnabled(sectionEl, enabled){
        if (!sectionEl) return;
        sectionEl.querySelectorAll('input, select, textarea, button').forEach(el => {
          if (el.id === 'request_mode') return; // keep hidden mode input
          el.disabled = !enabled;
        });
      }

      function applyMode(m){
        cards.forEach(c => c.classList.toggle('active', c && c.dataset.mode === m));
        const isOne = m === 'one_day';
        const isDirect = m === 'direct';
        if (oneBox) oneBox.style.display = isOne ? '' : 'none';
        if (req) req.required = isOne;
        if (evtStart) evtStart.required = !isOne; // 1일 Set은 일정 선택이 선택 사항
        if (modeInput) modeInput.value = m;
        // 폼 전환
        if (stdForm) stdForm.style.display = isDirect ? 'none' : '';
        if (directForm) directForm.style.display = isDirect ? '' : 'none';
        // 브라우저 required 검증 회피를 위해 비활성화/활성화 전환
        toggleSectionEnabled(stdForm, !isDirect);
        toggleSectionEnabled(directForm, isDirect);
        if (form) form.action = isDirect ? directAction : standardAction;
        if (submitBtn) submitBtn.textContent = isDirect ? '아티스트 지정 섭외' : (isOne ? '관리자의 추천셋' : '추천셋 즉시 생성');
        // 버튼 활성/비활성
        if (btnInstant) btnInstant.disabled = (m !== 'instant');
        if (btnOneDay) btnOneDay.disabled = (m !== 'one_day');
        if (btnDirect) btnDirect.disabled = (m !== 'direct');
      }
      cards.forEach(c => c && c.addEventListener('click', (e) => { e.preventDefault(); applyMode(c.dataset.mode); }));
      // Also switch mode explicitly when the mini buttons are clicked
      btnInstant?.addEventListener('click', (e)=>{ e.preventDefault(); applyMode('instant'); });
      btnOneDay?.addEventListener('click', (e)=>{ e.preventDefault(); applyMode('one_day'); });
      btnDirect?.addEventListener('click', (e)=>{ e.preventDefault(); applyMode('direct'); });

      // Auto-switch when user focuses into each section (prevents wrong mode submits)
      directForm?.addEventListener('focusin', ()=> applyMode('direct'));
      oneBox?.addEventListener('focusin', ()=> applyMode('one_day'));
      stdForm?.addEventListener('focusin', ()=> { if (modeInput?.value !== 'direct' && modeInput?.value !== 'one_day') applyMode('instant'); });
      applyMode(modeInput?.value || '{{ $prefMode ?? 'instant' }}');

      // direct request는 별도 페이지에서 처리
    })();
  </script>

  @include('public.partials.footer')
</body>

</html>
