@extends('layouts.admin')

@section('content')
<h1>추천안 관리 · 문의 #{{ $intake->id }} · {{ $intake->contact_name }} ({{ $intake->contact_email }})</h1>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
@if(session('err')) <div class="alert alert-danger">{{ session('err') }}</div> @endif

{{-- ===== 공유 패널 ===== --}}
@php
$preUrl = $intake->latest_token ? url('/r/'.$intake->latest_token) : '';
$canShare = (bool) $intake->latest_set_id;
@endphp
<div class="card" style="padding:12px; border:1px solid var(--border); border-radius:8px; margin-bottom:12px;">
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <strong>공유</strong>
        <button type="button" class="btn btn-sm btnShare" data-url="{{ route('admin.recommendations.share',$intake) }}" @disabled(!$canShare)>공유 링크</button>
        <button type="button" class="btn btn-sm btnRotate" data-url="{{ route('admin.recommendations.rotate',$intake) }}" @disabled(!$canShare)>링크 재생성</button>
        <button type="button" class="btn btn-sm btnRevoke" data-url="{{ route('admin.recommendations.revoke',$intake) }}" @disabled(!$preUrl)>공유 중단</button>
        <input class="input shareUrl" type="text" placeholder="공유 링크" readonly value="{{ $preUrl }}" style="min-width:320px;">
        <button type="button" class="btn btn-sm btnCopy" @disabled(!$preUrl)>복사</button>
        <a class="openPublic muted" href="{{ $preUrl ?: '#' }}" target="_blank" rel="noopener" style="{{ $preUrl ? '' : 'display:none;' }}">열기</a>
    </div>
</div>

{{-- 추천셋 없으면 먼저 생성 --}}
@if(!$set)
<div class="card" style="padding:16px; border:1px solid var(--border); border-radius:8px;">
    <p>아직 저장된 추천셋이 없습니다.</p>
    <form method="post" action="{{ route('admin.recommendations.create_set', $intake) }}">
        @csrf
        <button class="btn btn-primary" type="submit">비어있는 추천셋 만들기</button>
    </form>
</div>
@else {{-- ← 여기서부터 셋이 있을 때만 아래 그리드 보이도록 --}}

<div style="display:grid; grid-template-columns: 1.4fr .9fr; gap:16px; align-items:start;">

    {{-- ===== 왼쪽: 현재 담긴 아티스트 + 일괄 편집 + DnD ===== --}}
    <section style="border:1px solid var(--border); border-radius:8px; padding:12px; background: var(--surface); box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);">
        <h2 style="margin-top:0;">현재 추천 아티스트</h2>

        {{-- 일괄 편집(견적 채우기) --}}
        <div class="card" style="padding:10px; border:1px solid var(--border); border-radius:8px; margin-bottom:10px;">
            <form method="post" action="{{ route('admin.recommendations.items.bulk', $intake) }}" id="bulkValuesForm" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                @csrf
                <input type="hidden" name="mode" value="values">
                <label>범위:
                    <select name="scope" id="bulkScopeValues">
                        <option value="all">전체</option>
                        <option value="empty">빈 값만</option>
                        <option value="selected">선택행만</option>
                    </select>
                </label>
                <label>최소 <input type="number" name="min" min="0" style="width:120px;"></label>
                <label>최대 <input type="number" name="max" min="0" style="width:120px;"></label>
                <input type="hidden" name="selected_ids[]" id="bulkSelectedValues">
                <button class="btn btn-sm btn-primary" type="submit">↑ 일괄 채우기</button>
            </form>
            <form method="post" action="{{ route('admin.recommendations.items.bulk', $intake) }}" id="bulkFromFeeForm" style="display:flex; gap:8px; align-items:center; margin-top:8px; flex-wrap:wrap;">
                @csrf
                <input type="hidden" name="mode" value="from_fee">
                <label>범위:
                    <select name="scope" id="bulkScopeFee">
                        <option value="empty">빈 값만</option>
                        <option value="all">전체</option>
                        <option value="selected">선택행만</option>
                    </select>
                </label>
                <input type="hidden" name="selected_ids[]" id="bulkSelectedFee">
                <button class="btn btn-sm" type="submit">아티스트 요율에서 채우기</button>
            </form>
        </div>

        {{-- DnD 순서 저장용 --}}
        <form id="reorderForm" method="post" action="{{ route('admin.recommendations.items.reorder', $intake) }}" style="display:none;">
            @csrf
            <input type="hidden" name="ordered_ids" id="orderedIds">
        </form>

        <table class="table" id="itemsTable" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:34px;"><input type="checkbox" id="chkAll"></th>
                    <th style="width:64px;">순서</th>
                    <th>이름</th>
                    <th style="width:200px;">견적(최소~최대)</th>
                    <th style="width:150px;">상태</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>

            @php
            // 관계 컬렉션으로만 사용 (레거시 JSON items 아님)
            $items = ($set?->entries ?? collect())->sortBy('rank')->values();
            @endphp

            <tbody>
                @forelse($items as $item)
                <tr data-id="{{ $item->id }}" draggable="true">
                    <td><input type="checkbox" class="rowSel"></td>
                    <td class="dragHandle" title="드래그하여 순서 변경">
                        <button type="button" class="btn btn-sm" onclick="nudge(this,-1)">▲</button>
                        <button type="button" class="btn btn-sm" onclick="nudge(this, 1)">▼</button>
                        <div class="muted">#{{ $item->rank }}</div>
                    </td>
                    <td>
                        <strong>{{ $item->artist?->name ?? $item->artist?->stage_name ?? '미지정' }}</strong>
                        @if(!is_null($item->artist?->fame_score))
                        <div class="muted">Fame: {{ $item->artist->fame_score }}</div>
                        @endif
                        @php
                        $grs = $item->artist
                        ? (method_exists($item->artist, 'genresRelation') ? $item->artist->genresRelation->pluck('name')->all() : [])
                        : [];
                        @endphp
                        @if($grs)
                        <div class="muted">{{ implode(', ', $grs) }}</div>
                        @endif
                    </td>

                    <td>
                        <form method="post" action="{{ route('admin.recommendations.items.update', [$intake, $item]) }}" style="display:flex; gap:6px; align-items:center;">
                            @csrf @method('patch')
                            <input type="number" name="quoted_min" value="{{ $item->quoted_min }}" placeholder="최소" min="0" style="width:80px">
                            <span>~</span>
                            <input type="number" name="quoted_max" value="{{ $item->quoted_max }}" placeholder="최대" min="0" style="width:80px">
                            <button class="btn btn-sm">저장</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.recommendations.items.update', [$intake, $item]) }}" style="display:flex; gap:8px; align-items:center;">
                            @csrf @method('patch')
                            <label><input type="checkbox" name="fixed" value="1" @checked($item->fixed)> 고정</label>
                            <label><input type="checkbox" name="excluded" value="1" @checked($item->excluded)> 제외</label>
                            <button class="btn btn-sm">적용</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.recommendations.items.destroy', [$intake, $item]) }}" onsubmit="return confirm('삭제할까요?')" style="display:inline;">
                            @csrf @method('delete')
                            <button class="btn btn-sm btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="999" class="muted">아직 추천안에 담긴 아티스트가 없습니다.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:8px; display:flex; gap:8px;">
            <button class="btn" type="button" onclick="submitOrder()">순서 저장</button>
            <small class="muted">Tip: 행을 드래그해서 순서를 바꾸세요.</small>
        </div>
    </section>

    {{-- ===== 오른쪽: 고급 검색/담기 ===== --}}
    <aside style="border:1px solid var(--border); border-radius:8px; padding:12px; background: var(--surface); box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);">
        <h2 style="margin-top:0;">아티스트 검색/담기</h2>

        <div style="display:grid; gap:8px;">
            <input id="q" type="text" placeholder="이름, 키워드">

            <div style="display:flex; gap:8px;">
                <input id="min_fame" type="number" min="0" max="100" placeholder="Fame ≥">
                <input id="max_fame" type="number" min="0" max="100" placeholder="Fame ≤">
            </div>

            <div>
                <label>분야</label>
                <select id="discipline_id" style="width:100%;">
                    <option value="">(전체)</option>
                    @foreach($disciplines as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>장르(멀티)</label>
                <select id="genre_ids" multiple size="6" style="width:100%; min-height:140px;">
                    @foreach($genres as $g)
                    <option value="{{ $g->id }}">{{ $g->name }} @if($g->discipline) ({{ $g->discipline->name }}) @endif</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>태그(멀티)</label>
                <select id="tag_ids" multiple size="6" style="width:100%; min-height:140px;">
                    @foreach($tags as $t)
                    <option value="{{ $t->id }}">#{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>예산 범위</label>
                <div style="display:flex; gap:8px;">
                    <input id="budget_min" type="number" min="0" placeholder="최소">
                    <input id="budget_max" type="number" min="0" placeholder="최대">
                    <select id="currency" style="width:90px;">
                        <option value="KRW" selected>KRW</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>

            <button class="btn" type="button" onclick="runSearch()">검색</button>
        </div>

        <div id="resultBox" class="muted" style="margin-top:10px;">검색 결과가 여기에 표시됩니다.</div>
    </aside>

</div>
@endif {{-- ← 여기에서 if 블록을 닫습니다 --}}
@endsection

@push('scripts')
<script>
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    /* ====== 공유 패널 ====== */
    async function copyToClipboard(text) {
        if (!text) return false;
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
                ta.remove();
                return true;
            } catch {
                return false;
            }
        }
    }
    document.addEventListener('click', async (e) => {
        const shareBtn = e.target.closest('.btnShare');
        const rotateBtn = e.target.closest('.btnRotate');
        const revokeBtn = e.target.closest('.btnRevoke');
        const copyBtn = e.target.closest('.btnCopy');
        if (!(shareBtn || rotateBtn || revokeBtn || copyBtn)) return;

        const root = e.target.closest('.card');
        const input = root.querySelector('.shareUrl');
        const open = root.querySelector('.openPublic');
        const btn = shareBtn || rotateBtn || revokeBtn || copyBtn;

        if (copyBtn) {
            const ok = await copyToClipboard((input?.value || '').trim());
            alert(ok ? '공유 URL을 복사했습니다.' : '복사 실패');
            return;
        }

        if (revokeBtn && !confirm('정말 공유를 중단할까요?')) return;
        if (rotateBtn && !confirm('새 공유 URL로 재생성할까요?')) return;

        const url = btn.getAttribute('data-url');
        const buttons = root.querySelectorAll('button');
        buttons.forEach(b => b.disabled = true);
        btn.dataset.t = btn.textContent;
        btn.textContent = revokeBtn ? '중단 중...' : rotateBtn ? '재생성 중...' : '공유 중...';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });
            const ct = res.headers.get('content-type') || '';
            const data = ct.includes('json') ? await res.json().catch(() => ({})) : {};
            if (!res.ok) {
                alert(data.message || '요청 실패');
                return;
            }

            if (revokeBtn) {
                input.value = '';
                if (open) {
                    open.removeAttribute('href');
                    open.style.display = 'none';
                }
                alert('공유를 중단했습니다.');
                return;
            }

            const u = data.url || '';
            input.value = u;
            if (open) {
                if (u) {
                    open.href = u;
                    open.style.display = '';
                } else {
                    open.removeAttribute('href');
                    open.style.display = 'none';
                }
            }
            const copied = u ? await copyToClipboard(u) : false;
            alert(rotateBtn ? (copied ? '링크 재생성+복사 완료' : '링크 재생성 완료') :
                (copied ? '공유+복사 완료' : '공유 완료'));
        } catch (err) {
            console.error(err);
            alert('네트워크 오류');
        } finally {
            if (btn.dataset.t) btn.textContent = btn.dataset.t;
            buttons.forEach(b => b.disabled = false);
        }
    });

    /* ====== DnD 정렬 ====== */
    const tbody = document.querySelector('#itemsTable tbody');
    let dragEl = null;

    tbody?.addEventListener('dragstart', e => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        dragEl = tr;
        tr.style.opacity = '0.5';
    });
    tbody?.addEventListener('dragend', e => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        tr.style.opacity = '';
    });
    tbody?.addEventListener('dragover', e => {
        e.preventDefault();
        const tr = e.target.closest('tr');
        if (!tr || tr === dragEl) return;
        const rect = tr.getBoundingClientRect();
        const before = (e.clientY - rect.top) < rect.height / 2;
        tbody.insertBefore(dragEl, before ? tr : tr.nextSibling);
    });

    function nudge(btn, dir) {
        const tr = btn.closest('tr');
        if (dir < 0 && tr.previousElementSibling) tbody.insertBefore(tr, tr.previousElementSibling);
        else if (dir > 0 && tr.nextElementSibling) tbody.insertBefore(tr.nextElementSibling, tr);
    }

    function submitOrder() {
        const ids = [...document.querySelectorAll('#itemsTable tbody tr')].map(tr => tr.dataset.id);
        const fd = new FormData();
        ids.forEach((id, i) => fd.append('ordered_ids[' + i + ']', id));
        fetch(`{{ route('admin.recommendations.items.reorder', $intake) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            body: fd
        }).then(r => r.ok ? location.reload() : alert('순서 저장 실패'));
    }

    /* ====== 일괄 편집 선택행 수집 ====== */
    const chkAll = document.getElementById('chkAll');
    chkAll?.addEventListener('change', () => {
        document.querySelectorAll('#itemsTable .rowSel').forEach(cb => cb.checked = chkAll.checked);
    });

    function collectSelectedIds() {
        return [...document.querySelectorAll('#itemsTable .rowSel:checked')]
            .map(cb => cb.closest('tr')?.dataset.id).filter(Boolean);
    }

    document.getElementById('bulkValuesForm')?.addEventListener('submit', e => {
        const scope = document.getElementById('bulkScopeValues').value;
        if (scope === 'selected') {
            const ids = collectSelectedIds();
            if (!ids.length) {
                e.preventDefault();
                alert('선택된 행이 없습니다.');
                return;
            }
            const hidden = document.getElementById('bulkSelectedValues');
            if (hidden) hidden.remove();
            const form = e.target;
            ids.forEach((id, i) => {
                const h = document.createElement('input');
                h.type = 'hidden';
                h.name = `selected_ids[${i}]`;
                h.value = id;
                form.appendChild(h);
            });
        }
    });

    document.getElementById('bulkFromFeeForm')?.addEventListener('submit', e => {
        const scope = document.getElementById('bulkScopeFee').value;
        if (scope === 'selected') {
            const ids = collectSelectedIds();
            if (!ids.length) {
                e.preventDefault();
                alert('선택된 행이 없습니다.');
                return;
            }
            const form = e.target;
            ids.forEach((id, i) => {
                const h = document.createElement('input');
                h.type = 'hidden';
                h.name = `selected_ids[${i}]`;
                h.value = id;
                form.appendChild(h);
            });
        }
    });

    /* ====== 고급 검색 ====== */
    async function runSearch() {
        const params = new URLSearchParams();
        const q = document.getElementById('q').value.trim();
        const min_fame = document.getElementById('min_fame').value;
        const max_fame = document.getElementById('max_fame').value;
        const discipline_id = document.getElementById('discipline_id').value;
        const budget_min = document.getElementById('budget_min').value;
        const budget_max = document.getElementById('budget_max').value;
        const currency = document.getElementById('currency').value;

        if (q) params.set('q', q);
        if (min_fame) params.set('min_fame', min_fame);
        if (max_fame) params.set('max_fame', max_fame);
        if (discipline_id) params.set('discipline_id', discipline_id);
        if (budget_min) params.set('budget_min', budget_min);
        if (budget_max) params.set('budget_max', budget_max);
        if (currency) params.set('currency', currency);

        const genresSel = document.getElementById('genre_ids');
        const tagsSel = document.getElementById('tag_ids');
        [...genresSel.selectedOptions].forEach(o => params.append('genre_ids[]', o.value));
        [...tagsSel.selectedOptions].forEach(o => params.append('tag_ids[]', o.value));

        const box = document.getElementById('resultBox');
        box.textContent = '검색 중...';
        try {
            const res = await fetch(`{{ route('admin.artist_search') }}?` + params.toString(), {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            const rows = json.results || [];
            if (!rows.length) {
                box.textContent = '검색 결과 없음';
                return;
            }

            box.innerHTML = `
        <table class="table" style="width:100%;">
          <thead><tr><th>이름</th><th>Fame</th><th>장르/태그</th><th>요율</th><th></th></tr></thead>
          <tbody>
            ${rows.map(r=>`
              <tr>
                <td>${escapeHtml(r.name)}</td>
                <td>${r.fame ?? '-'}</td>
                <td class="muted">${escapeHtml((r.genres||[]).join(', '))}${(r.tags||[]).length ? ' · #'+escapeHtml((r.tags||[]).join(' #')) : ''}</td>
                <td class="muted">${(r.fee_min!=null || r.fee_max!=null) ? `${r.currency} ${fmtNum(r.fee_min)} ~ ${fmtNum(r.fee_max)} (${r.unit})` : '-'}</td>
                <td>
                  <form method="post" action="{{ route('admin.recommendations.items.store', $intake) }}">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="artist_id" value="${r.id}">
                    <button class="btn btn-sm">담기</button>
                  </form>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>`;
        } catch (e) {
            console.error(e);
            box.textContent = '검색 실패';
        }
    }

    function fmtNum(v) {
        if (v == null) return '-';
        try {
            return Number(v).toLocaleString();
        } catch {
            return v;
        }
    }

    function escapeHtml(s) {
        return (s || '').replace(/[&<>"']/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [m]));
    }
</script>
@endpush
