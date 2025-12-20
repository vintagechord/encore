@extends('layouts.admin')

@push('head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --bg: #050912;
      --surface: #0f1729;
      --surface-alt: #152033;
      --border: #1f2b41;
      --border-soft: #273554;
      --text: #e6edff;
      --muted: #97a6c9;
      --accent: #6366f1;
      --accent-hover: #818cf8;
      --danger: #ef4444;
      --warning: #f59e0b;
      --success: #34d399;
    }

    body {
      font-family: -apple-system, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
      color: var(--text);
      background: var(--bg);
    }

    h1 {
      margin: 0 0 12px;
      font-size: 22px;
    }

    p {
      margin: 0 0 16px;
    }

    a {
      color: #8da2fb;
      text-decoration: none;
      transition: color .2s ease;
    }

    a:hover {
      color: #b3c0ff;
      text-decoration: underline;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 12px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 16px 40px rgba(4, 8, 18, 0.45);
    }

    th,
    td {
      border-bottom: 1px solid var(--border);
      padding: 10px;
      font-size: 14px;
      vertical-align: top;
    }

    th {
      background: rgba(99, 102, 241, 0.14);
      text-align: left;
      color: var(--text);
    }

    tbody tr:nth-child(even) {
      background: var(--surface-alt);
    }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      align-items: center;
    }

    .btn {
      padding: 6px 10px;
      border: 1px solid var(--accent);
      border-radius: 6px;
      background: var(--accent);
      color: #fff;
      cursor: pointer;
      font-weight: 600;
      transition: background .2s ease, border-color .2s ease, opacity .2s ease;
    }

    .btn:hover:not(:disabled) {
      background: var(--accent-hover);
      border-color: var(--accent-hover);
    }

    .btn:disabled {
      opacity: .45;
      cursor: not-allowed;
    }

    .btn.btnRevoke,
    .modal .close {
      background: var(--danger);
      border-color: var(--danger);
    }

    .btn.btnRevoke:hover:not(:disabled),
    .modal .close:hover {
      background: #f87171;
      border-color: #f87171;
    }

    .btn.btnRotate {
      background: var(--warning);
      border-color: var(--warning);
      color: #1f2937;
    }

    .btn.btnRotate:hover:not(:disabled) {
      background: #fbbf24;
      border-color: #fbbf24;
    }

    .input {
      min-width: 320px;
      max-width: 100%;
      padding: 6px 8px;
      border: 1px solid var(--border-soft);
      border-radius: 6px;
      background: var(--surface-alt);
      color: var(--text);
    }

    .input:focus {
      border-color: var(--accent);
      outline: none;
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
    }

    .muted {
      color: var(--muted);
      font-size: 12px;
    }

    .flash {
      animation: rowflash 1200ms ease-out;
    }

    @keyframes rowflash {
      0% {
        background: rgba(99, 102, 241, 0.25);
      }

      100% {
        background: transparent;
      }
    }

    /* 모달 */
    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(5, 9, 18, 0.75);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 50;
    }

    .modal {
      background: var(--surface);
      border-radius: 12px;
      max-width: 800px;
      width: clamp(320px, 92vw, 800px);
      max-height: 80vh;
      overflow: hidden;
      border: 1px solid var(--border);
      box-shadow: 0 24px 60px rgba(2, 4, 12, 0.6);
    }

    .modal header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      background: rgba(15, 23, 41, 0.95);
      backdrop-filter: saturate(160%) blur(10px);
    }

    .modal h3 {
      margin: 0;
      font-size: 18px;
    }

    .modal .close {
      color: #fff;
      border-radius: 6px;
      padding: 6px 10px;
      cursor: pointer;
    }

    .modal .body {
      padding: 14px 16px;
    }

    .tag {
      display: inline-block;
      font-size: 12px;
      border-radius: 999px;
      padding: 2px 8px;
      border: 1px solid var(--border);
      background: var(--surface-alt);
      color: var(--muted);
    }

    .tag.rotated {
      border-color: var(--warning);
      background: rgba(245, 158, 11, 0.2);
      color: #fbbf24;
    }

    .tag.shared {
      border-color: var(--accent);
      background: rgba(99, 102, 241, 0.2);
      color: #b3c0ff;
    }

    .tag.issued {
      border-color: var(--success);
      background: rgba(52, 211, 153, 0.2);
      color: var(--success);
    }

    .tag.revoked {
      border-color: var(--danger);
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
    }

    .toolbar {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      margin: 0 0 8px;
    }

    .toolbar input[type="number"] {
      padding: 6px 8px;
      border: 1px solid var(--border-soft);
      border-radius: 6px;
      background: var(--surface-alt);
      color: var(--text);
    }

    .toolbar input[type="number"]:focus {
      border-color: var(--accent);
      outline: none;
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
    }

    .filters {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .filters label {
      display: flex;
      gap: 4px;
      align-items: center;
      font-size: 12px;
      color: var(--muted);
      border: 1px solid var(--border);
      border-radius: 999px;
      padding: 2px 8px;
      background: var(--surface-alt);
    }

    .filters input[type="checkbox"] {
      accent-color: var(--accent);
    }

    .tzbtn.active {
      box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.35);
    }
  </style>
@endpush

@section('content')
  <h1>문의 목록</h1>
  <p><a href="{{ route('inquiry.create') }}">새 문의</a></p>

  <!-- Export CSV + 시간 토글 -->
  <div class="admin-intakes__export" style="margin:8px 0 8px; display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
    <a href="{{ route('admin.intakes.export', request()->only(['q','from','to','limit'])) }}" class="btn">Export CSV</a>
    <div class="tz-toggle" style="display:flex; gap:8px; align-items:center;">
      <span class="muted">Time:</span>
      <button type="button" class="btn tzbtn" data-tz="UTC">UTC</button>
      <button type="button" class="btn tzbtn" data-tz="LOCAL">Local</button>
      <small class="tz-label muted"></small>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>성함</th>
        <th>이메일</th>
        <th>접수일</th>
        <th>상태</th>
        <th>액션</th>
      </tr>
    </thead>
    <tbody>
      @forelse($intakes as $i)
      <tr>
        <td>{{ $i->id }}</td>
        <td>{{ $i->contact_name }}</td>
        <td>{{ $i->contact_email }}</td>
        <td>
          @php $isoCreated = optional($i->created_at)?->toIso8601String(); @endphp
          <span class="dt" data-iso="{{ $isoCreated }}">{{ $isoCreated }}</span>
        </td>
        <td>
          <form method="post" action="{{ route('admin.intakes.status', ['intake'=>$i->id]) }}" style="display:flex;gap:6px;align-items:center">
            @csrf
            <select name="status" style="background:var(--surface-alt);color:var(--text);border:1px solid var(--border-soft);border-radius:6px;padding:4px 6px">
              @php $st=$i->status ?: 'new'; @endphp
              <option value="new" @selected($st==='new')>접수 완료</option>
              <option value="processing" @selected($st==='processing')>견적 확인</option>
              <option value="recommended" @selected($st==='recommended')>결제 하기</option>
              <option value="closed" @selected($st==='closed')>결제 완료</option>
              <option value="booked" @selected($st==='booked')>섭외 완료</option>
              <option value="completed" @selected($st==='completed')>행사 완료</option>
            </select>
            <button class="btn" type="submit">저장</button>
          </form>
        </td>
        <td>
          <div class="actions">
            <a class="btn" href="{{ route('admin.recommendations', ['intake' => $i->id]) }}">추천안 보기</a>

            @php
            $__preUrl = $i->latest_token ? url('/r/'.$i->latest_token) : '';
            $__sentISO = $i->latest_sent_at ? \Illuminate\Support\Carbon::parse($i->latest_sent_at, 'UTC')->toIso8601String() : null;

            /**
            * 추천셋 존재 판단 신호(OR)
            * 1) latest_set_id 컬럼
            * 2) latestSet 리レー션이 로드되어 있다면 그 id
            * 3) withCount('recommendationSets') 결과
            * 4) 이미 발급된 토큰이 있으면 사실상 추천셋이 있다고 간주
            */
            $__hasSet =
            !empty($i->latest_set_id)
            || (method_exists($i,'relationLoaded') && $i->relationLoaded('latestSet') && optional($i->latestSet)->id)
            || (!empty($i->recommendation_sets_count) && $i->recommendation_sets_count > 0)
            || !empty($i->latest_token);

            $__disabled = $__hasSet ? '' : 'disabled'; // 공유/재생성 버튼 기준
            $__title = $__hasSet ? '공유 링크 생성/복사' : '추천셋을 찾지 못했습니다(관리자 조회 기준)';
            $__revDisable = $__preUrl ? '' : 'disabled';
            $__copyDisable = $__preUrl ? '' : 'disabled';
            @endphp

            <button type="button" class="btn btnShare"
              data-url="{{ route('admin.recommendations.share', ['intake' => $i->id]) }}"
              {{ $__disabled }} title="{{ $__title }}">공유 링크</button>

            <button type="button" class="btn btnRotate"
              data-url="{{ route('admin.recommendations.rotate', ['intake' => $i->id]) }}"
              {{ $__disabled }} title="공유 URL 재생성(토큰 회전)">링크 재생성</button>

            <button type="button" class="btn btnRevoke"
              data-url="{{ route('admin.recommendations.revoke', ['intake' => $i->id]) }}"
              {{ $__revDisable }} title="공유 중단(현재 공개 링크 무효화)">공유 중단</button>

            <button type="button" class="btn btnLog"
              data-url="{{ route('admin.recommendations.share_log', ['intake' => $i->id]) }}"
              title="공유/회전/발급 이력 보기">활동 로그</button>

            <input class="input shareUrl" type="text" placeholder="공유 링크" readonly value="{{ $__preUrl }}">
            <button type="button" class="btn btnCopy" {{ $__copyDisable }} title="현재 URL 복사">복사</button>
            <a class="openPublic muted" href="{{ $__preUrl ?: '#' }}" target="_blank" rel="noopener noreferrer"
              style="margin-left:6px; {{ $__preUrl ? '' : 'display:none;' }}">열기</a>

            <span class="sentAt muted">
              @if($__sentISO)
              공유됨 <span class="dt" data-iso="{{ $__sentISO }}">{{ $__sentISO }}</span>
              @endif
            </span>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">데이터가 없습니다.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <!-- 모달 -->
  <div class="modal-backdrop" id="logModal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="logTitle">
      <header>
        <h3 id="logTitle">활동 로그</h3>
        <button class="close" data-close>닫기</button>
      </header>
      <div class="body">
        <div class="toolbar">
          <label>최대 행 <input type="number" id="logLimit" value="20" min="1" max="200" style="width:74px"></label>
          <div class="filters" id="typeFilters">
            <span class="muted">이벤트:</span>
            <label><input type="checkbox" class="evtFilter" value="issued" checked> issued</label>
            <label><input type="checkbox" class="evtFilter" value="shared" checked> shared</label>
            <label><input type="checkbox" class="evtFilter" value="rotated" checked> rotated</label>
            <label><input type="checkbox" class="evtFilter" value="revoked" checked> revoked</label>
          </div>
          <button class="btn" id="btnReload">새로고침</button>
        </div>

        <table>
          <thead>
            <tr>
              <th style="width:90px">ID</th>
              <th style="width:120px">이벤트</th>
              <th>토큰</th>
              <th style="width:220px">시각</th>
            </tr>
          </thead>
          <tbody id="logRows">
            <tr>
              <td colspan="4" class="muted">불러오는 중…</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script data-cfasync="false">
    (function() {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      /* ===== 시간대 토글 ===== */
      const STORE_KEY = 'tzpref'; // 'UTC' | 'LOCAL'
      const LOCAL_TZ = 'Asia/Seoul';

      function fmtISOToView(iso, mode) {
        if (!iso) return '';
        const d = new Date(iso);
        if (isNaN(d.getTime())) return iso;
        if (mode === 'UTC') return d.toISOString().replace('T', ' ').replace('Z', ' UTC');
        try {
          return new Intl.DateTimeFormat(undefined, {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: LOCAL_TZ,
            timeZoneName: 'short'
          }).format(d);
        } catch {
          return d.toLocaleString();
        }
      }
      const currentMode = () => localStorage.getItem(STORE_KEY) || 'LOCAL';

      function applyMode(mode) {
        document.querySelectorAll('.dt[data-iso]').forEach(el => {
          const iso = el.getAttribute('data-iso') || '';
          el.textContent = fmtISOToView(iso, mode);
        });
        localStorage.setItem(STORE_KEY, mode);
        const label = document.querySelector('.tz-label');
        if (label) label.textContent = (mode === 'UTC' ? 'UTC' : LOCAL_TZ);
        document.querySelectorAll('.tzbtn').forEach(b => {
          b.classList.toggle('active', b.getAttribute('data-tz') === mode);
        });
      }

      function initTZ() {
        document.querySelectorAll('.tzbtn').forEach(b => {
          b.addEventListener('click', () => applyMode(b.getAttribute('data-tz')));
        });
        applyMode(currentMode());
      }

      /* ===== 공용 포맷 ===== */
      const pad = n => String(n).padStart(2, '0');
      const fmt = dt => `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())} ${pad(dt.getHours())}:${pad(dt.getMinutes())}`;

      /* ===== 복사 fallback ===== */
      async function copyToClipboard(text) {
        if (!text) throw new Error('empty');
        try {
          await navigator.clipboard.writeText(text);
          return true;
        } catch {
          try {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            return true;
          } catch {
            return false;
          }
        }
      }

      /* ===== 공유/재생성/중단/복사/로그 ===== */
      async function handleShareClick(e) {
        const shareBtn = e.target.closest('.btnShare');
        const rotateBtn = e.target.closest('.btnRotate');
        const revokeBtn = e.target.closest('.btnRevoke');
        const copyBtn = e.target.closest('.btnCopy');
        const logBtn = e.target.closest('.btnLog');
        const btn = shareBtn || rotateBtn || revokeBtn || copyBtn || logBtn;
        if (!btn) return;
        if (btn.disabled) return;

        if (logBtn) {
          openLogModal(btn.getAttribute('data-url'));
          return;
        }

        const isRotate = !!rotateBtn;
        const isRevoke = !!revokeBtn;
        const isCopy = !!copyBtn;

        const cell = btn.closest('td');
        const input = cell.querySelector('.shareUrl');
        const badge = cell.querySelector('.sentAt');
        const open = cell.querySelector('.openPublic');

        if (isCopy) {
          const ok = await copyToClipboard((input?.value || '').trim());
          alert(ok ? '공유 URL을 클립보드에 복사했습니다.' : '복사에 실패했습니다.');
          return;
        }

        const url = btn.getAttribute('data-url');
        if (isRevoke && !confirm('정말 공유를 중단할까요?\n이전 공개 링크는 즉시 404가 됩니다.')) return;
        if (isRotate && !confirm('새 공유 URL로 재생성할까요?\n기존 공개 링크는 더 이상 유효하지 않습니다.')) return;

        const buttons = [...cell.querySelectorAll('.btn')];
        buttons.forEach(b => b.disabled = true);
        btn.dataset.originalText = btn.textContent;
        btn.textContent = isRevoke ? '중단 중...' : isRotate ? '재생성 중...' : '공유 중...';

        try {
          const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          let data = {};
          const ct = res.headers.get('content-type') || '';
          if (ct.includes('application/json')) data = await res.json().catch(() => ({}));

          if (!res.ok) {
            if (res.status === 419) alert('세션이 만료되었습니다. 페이지를 새로고침하고 다시 시도해주세요.');
            else if (res.status === 403) alert('권한이 없습니다.');
            else if (res.status === 405) alert('요청 메서드가 올바르지 않습니다.');
            else alert(data.message || '요청 처리에 실패했습니다.');
            return;
          }

          if (isRevoke) {
            input.value = '';
            if (open) {
              open.removeAttribute('href');
              open.style.display = 'none';
            }
            const d = new Date(data.revoked_at || Date.now());
            if (badge) badge.textContent = `공유 중단 ${fmt(d)}`;
            const revokeBtnEl = cell.querySelector('.btnRevoke');
            if (revokeBtnEl) {
              revokeBtnEl.disabled = true;
              revokeBtnEl.title = '현재 공유 중이 아닙니다';
            }
            const copyBtnEl = cell.querySelector('.btnCopy');
            if (copyBtnEl) copyBtnEl.disabled = true;
            const row = cell.closest('tr');
            if (row) {
              row.classList.remove('flash');
              void row.offsetWidth;
              row.classList.add('flash');
            }
            alert('공유를 중단하고 공개 링크를 무효화했습니다.');
            return;
          }

          // share/rotate
          input.value = data.url || '';
          if (open) {
            if (data.url) {
              open.href = data.url;
              open.style.display = '';
            } else {
              open.removeAttribute('href');
              open.style.display = 'none';
            }
          }
          if (data.sent_at) {
            badge.innerHTML = `공유됨 <span class="dt" data-iso="${data.sent_at}"></span>`;
            applyMode(currentMode());
          }
          const revokeBtnEl = cell.querySelector('.btnRevoke');
          if (revokeBtnEl) {
            revokeBtnEl.disabled = false;
            revokeBtnEl.title = '공유 중단(현재 공개 링크 무효화)';
          }
          const copyBtnEl = cell.querySelector('.btnCopy');
          if (copyBtnEl) copyBtnEl.disabled = !Boolean(data.url);

          const row = cell.closest('tr');
          if (row) {
            row.classList.remove('flash');
            void row.offsetWidth;
            row.classList.add('flash');
          }

          let copied = false;
          if (data?.url) copied = await copyToClipboard(data.url);
          alert(isRotate ? (copied ? '새 공유 URL로 재생성하고 복사했습니다.' : '새 공유 URL로 재생성했습니다.') :
            (copied ? '공유 링크를 클립보드에 복사했습니다.' : '공유 링크를 생성했습니다.'));
        } catch (err) {
          console.error(err);
          alert('네트워크 오류로 실패했습니다.');
        } finally {
          if (btn.dataset.originalText) btn.textContent = btn.dataset.originalText;
          buttons.forEach(b => b.disabled = false);
        }
      }
      document.addEventListener('click', handleShareClick);

      /* ===== 로그 모달 ===== */
      const modal = document.getElementById('logModal');
      const logRows = document.getElementById('logRows');
      const logLimit = document.getElementById('logLimit');
      const btnReload = document.getElementById('btnReload');
      const filterBox = document.getElementById('typeFilters');
      let currentLogUrl = null;
      let cachedEvents = [];

      function openLogModal(urlBase) {
        currentLogUrl = urlBase;
        modal.style.display = 'flex';
        loadLog(true);
      }

      function closeLogModal() {
        modal.style.display = 'none';
        currentLogUrl = null;
        cachedEvents = [];
      }
      modal.addEventListener('click', e => {
        if (e.target === modal || e.target.closest('[data-close]')) closeLogModal();
      });
      btnReload.addEventListener('click', () => loadLog(true));
      filterBox.addEventListener('change', () => renderRows(cachedEvents));

      async function loadLog(forceFetch) {
        if (!currentLogUrl) return;
        const limit = Math.max(1, Math.min(200, parseInt(logLimit.value || '20', 10)));
        if (forceFetch) {
          logRows.innerHTML = `<tr><td colspan="4" class="muted">불러오는 중…</td></tr>`;
          try {
            const res = await fetch(`${currentLogUrl}?limit=${limit}`, {
              credentials: 'same-origin',
              headers: {
                'Accept': 'application/json'
              }
            });
            const data = await res.json();
            cachedEvents = data?.events || [];
          } catch (e) {
            console.error(e);
            logRows.innerHTML = `<tr><td colspan="4" class="muted">로드 실패</td></tr>`;
            return;
          }
        }
        renderRows(cachedEvents);
      }

      function renderRows(allEvents) {
        const enabled = [...filterBox.querySelectorAll('.evtFilter:checked')].map(cb => cb.value);
        const events = allEvents.filter(ev => enabled.includes(ev.event_type));
        if (!events.length) {
          logRows.innerHTML = `<tr><td colspan="4" class="muted">이력이 없습니다.</td></tr>`;
          return;
        }
        const mode = currentMode();
        logRows.innerHTML = events.map(ev => {
          const tag = `<span class="tag ${ev.event_type}">${ev.event_type}</span>`;
          const ts = fmtISOToView(ev.created_at || '', mode);
          const tzLabel = (mode === 'UTC' ? 'UTC' : 'Asia/Seoul');
          return `<tr>
          <td>${ev.id}</td>
          <td>${tag}</td>
          <td><code>${ev.token}</code></td>
          <td>${ts} <span class="muted">(${tzLabel})</span></td>
        </tr>`;
        }).join('');
      }

      /* ===== 초기화 ===== */
      initTZ();
    })();
  </script>
@endpush
