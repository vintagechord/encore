@extends('layouts.admin')

@section('content')
<h1>{{ $artist->exists ? '아티스트 수정' : '아티스트 생성' }}</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="post" action="{{ $artist->exists ? route('admin.artists.update',$artist) : route('admin.artists.store') }}" enctype="multipart/form-data">
    @csrf
    @if($artist->exists) @method('put') @endif

    {{-- 기본 정보 --}}
    <style>
      .form-grid{ display:grid; gap:10px; grid-template-columns: 1fr 1fr; align-items:start; }
      .chips{ display:flex; gap:6px; flex-wrap:wrap; margin-top:6px; }
      .chip{ display:inline-flex; align-items:center; gap:6px; padding:4px 8px; border-radius:999px; border:1px solid var(--border); background: var(--card-alt); color: var(--muted); }
      .chip button{ background:transparent; border:none; color:inherit; cursor:pointer; padding:0 2px; }
      .picker-row{ display:flex; gap:8px; align-items:center; }
      .full{ grid-column: 1 / -1; }
      .preview{ display:block; width:180px; height:auto; border-radius:10px; border:1px solid var(--border); }
      /* Consistent buttons */
      .picker-row .btn.sm{ min-width:64px; height:32px; }
      .form-actions{ display:flex; gap:8px; margin-top:12px; }
      .form-actions .btn{ min-width:72px; height:36px; }
      /* Fee table: center headings and cells */
      #feeTable th, #feeTable td{ text-align:center; vertical-align:middle; }
      #feeTable input, #feeTable select{ height:32px; }
      .fee-actions{ display:flex; justify-content:flex-end; margin-top:8px; }
    </style>

    <div class="form-grid">
        <div>
            <label>분야(Discipline)<br>
                <select id="discipline_id" name="discipline_id" required style="width:100%;">
                    <option value="">(선택)</option>
                    @foreach($disciplines as $d)
                    <option value="{{ $d->id }}" @selected(old('discipline_id',$artist->discipline_id)==$d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </label>
            @if($disciplines->isEmpty())
            <div class="muted" style="margin-top:6px;">
              분류가 비어 있습니다. <a href="{{ route('admin.taxonomies.index') }}">분류 관리</a>에서 추가하거나,
              <form method="post" action="{{ route('admin.taxonomies.seed') }}" style="display:inline;">
                @csrf <button class="btn btn-sm" type="submit">기본 분류 채우기</button>
              </form>
            </div>
            @endif
        </div>

        <div>
            <label>노출명(스테이지명)<br>
                <input type="text" name="stage_name" value="{{ old('stage_name',$artist->stage_name) }}" required style="width:100%;">
            </label>
        </div>

        <div>
            <label>본명/법인명<br>
                <input type="text" name="legal_name" value="{{ old('legal_name',$artist->legal_name) }}" style="width:100%;">
            </label>
        </div>

        <div>
            <label>유명세(0~100)<br>
                <input type="number" min="0" max="100" name="fame_score" value="{{ old('fame_score',$artist->fame_score ?? 50) }}" style="width:100%;">
            </label>
            <div style="margin-top:6px;">
                <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$artist->active ?? true))> 사용</label>
            </div>
        </div>
    </div>

    {{-- 분류(장르/태그) --}}
    <div class="form-grid full" style="margin-top:12px; grid-template-columns: 1fr 1fr;">
        <div>
            <label>장르(복수 선택)<br></label>
            @php $selGenres = collect(old('genre_ids', $artist->genresRelation->pluck('id')->all() ?? []))->map(fn($id)=> (int)$id)->all(); @endphp
            <div class="picker-row">
                <select id="genrePicker" style="width:100%">
                    <option value="">장르 선택…</option>
                    @foreach($genres as $g)
                    <option value="{{ $g->id }}" data-disc="{{ $g->discipline_id }}">{{ $g->name }} @if($g->discipline) ({{ $g->discipline->name }}) @endif</option>
                    @endforeach
                </select>
                <button type="button" class="btn sm" id="addGenre">추가</button>
            </div>
            <div id="genreChips" class="chips"></div>
        </div>
        <div>
            <label>태그(복수 선택)<br></label>
            @php $selTags = collect(old('tag_ids', $artist->tags->pluck('id')->all() ?? []))->map(fn($id)=> (int)$id)->all(); @endphp
            <div class="picker-row">
                <select id="tagPicker" style="width:100%">
                    <option value="">태그 선택…</option>
                    @foreach($tags as $t)
                    <option value="{{ $t->id }}">#{{ $t->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn sm" id="addTag">추가</button>
            </div>
            <div id="tagChips" class="chips"></div>
        </div>
    </div>

    <div class="form-grid full" style="margin-top:12px; grid-template-columns: 1fr 1fr;">
      <div>
        <label>아티스트 이미지<br>
          <input type="file" name="image" accept="image/*">
        </label>
        @php $imgPath = $artist->image_path ? asset('storage/'.$artist->image_path) : ($artist->image_url ?? null); @endphp
        @if($imgPath)
          <img class="preview" src="{{ $imgPath }}" alt="미리보기">
        @endif
      </div>
    </div>

    {{-- 외부 링크 / 소개 --}}
    <div style="display:grid; gap:10px; grid-template-columns: 1fr 1fr; margin-top:12px;">
        <div>
            <label>외부링크(JSON 또는 key/value 배열)<br>
                <textarea name="external_links" rows="6" style="width:100%;" placeholder='{"instagram":"...","site":"..."}'>{{ is_array(old('external_links',$artist->external_links)) ? json_encode(old('external_links',$artist->external_links), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : old('external_links') }}</textarea>
            </label>
        </div>
        <div>
            <label>소개(Bio)<br>
                <textarea name="bio" rows="6" style="width:100%;">{{ old('bio',$artist->bio) }}</textarea>
            </label>
        </div>
    </div>

    {{-- === 요율(다중) === --}}
    <fieldset style="margin:12px 0; padding:12px; border:1px solid var(--border); background: var(--surface); border-radius:10px;">
        <legend>섭외비(여러 개 추가 가능)</legend>

        <table id="feeTable" class="table" style="width:100%;">
            <colgroup>
                <col style="width:80px">
                <col style="width:90px">
                <col style="width:120px">
                <col style="width:140px">
                <col style="width:140px">
                <col style="width:200px">
                <col style="width:70px">
                <col style="width:90px">
            </colgroup>
            <thead>
                <tr>
                    <th>통화</th>
                    <th>단위</th>
                    <th>지역</th>
                    <th>최소</th>
                    <th>최대</th>
                    <th>메모</th>
                    <th>사용</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $rows = old('fees', $artist->fees->map(fn($f)=>$f->toArray())->all() ?? []); @endphp
                @forelse($rows as $idx => $f)
                <tr>
                    <td><input name="fees[{{ $idx }}][currency]" value="{{ $f['currency'] ?? 'KRW' }}" maxlength="3" style="width:70px"></td>
                    <td>
                        <select name="fees[{{ $idx }}][unit]">
                            @foreach(['appearance'=>'출연','set'=>'세트','hour'=>'시간','day'=>'일'] as $val=>$txt)
                            <option value="{{ $val }}" @selected(($f['unit'] ?? 'appearance' )==$val)>{{ $txt }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input name="fees[{{ $idx }}][region_code]" value="{{ $f['region_code'] ?? '' }}" placeholder="KR, US-CA" style="width:100px"></td>
                    <td><input type="number" name="fees[{{ $idx }}][min_fee]" value="{{ $f['min_fee'] ?? '' }}" min="0" style="width:120px"></td>
                    <td><input type="number" name="fees[{{ $idx }}][max_fee]" value="{{ $f['max_fee'] ?? '' }}" min="0" style="width:120px"></td>
                    <td><input name="fees[{{ $idx }}][notes]" value="{{ $f['notes'] ?? '' }}" style="width:160px"></td>
                    <td style="text-align:center;">
                        <input type="checkbox" name="fees[{{ $idx }}][is_active]" value="1" @checked(($f['is_active'] ?? true))>
                    </td>
                    <td>
                        @if(!empty($f['id'])) <input type="hidden" name="fees[{{ $idx }}][id]" value="{{ $f['id'] }}"> @endif
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFeeRow(this)">삭제</button>
                    </td>
                </tr>
                @empty
                {{-- 초기 1행 --}}
                <tr>
                    <td><input name="fees[0][currency]" value="KRW" maxlength="3" style="width:70px"></td>
                    <td>
                        <select name="fees[0][unit]">
                            @foreach(['appearance'=>'출연','set'=>'세트','hour'=>'시간','day'=>'일'] as $val=>$txt)
                            <option value="{{ $val }}">{{ $txt }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input name="fees[0][region_code]" placeholder="KR, US-CA" style="width:100px"></td>
                    <td><input type="number" name="fees[0][min_fee]" min="0" style="width:120px"></td>
                    <td><input type="number" name="fees[0][max_fee]" min="0" style="width:120px"></td>
                    <td><input name="fees[0][notes]" style="width:160px"></td>
                    <td style="text-align:center;"><input type="checkbox" name="fees[0][is_active]" value="1" checked></td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeFeeRow(this)">삭제</button></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="fee-actions">
          <button type="button" class="btn sm" onclick="addFeeRow()">+ 요율 추가</button>
        </div>
    </fieldset>

    <div class="form-actions">
        <button class="btn btn-primary" type="submit">{{ $artist->exists ? '저장' : '생성' }}</button>
        <a class="btn" href="{{ route('admin.artists.index') }}">목록</a>
    </div>
</form>

@push('scripts')
<script>
  (function(){
    const discSel = document.getElementById('discipline_id');
    const genrePicker = document.getElementById('genrePicker');
    const addGenreBtn = document.getElementById('addGenre');
    const genreChips = document.getElementById('genreChips');
    const tagPicker = document.getElementById('tagPicker');
    const addTagBtn = document.getElementById('addTag');
    const tagChips = document.getElementById('tagChips');

    function makeChip(id, label, name){
      const chip = document.createElement('span');
      chip.className = 'chip';
      chip.dataset.id = id;
      chip.innerHTML = label + ' <button type="button" aria-label="삭제">×</button>';
      const hid = document.createElement('input');
      hid.type = 'hidden';
      hid.name = name + '[]';
      hid.value = id;
      chip.appendChild(hid);
      chip.querySelector('button').addEventListener('click', ()=> chip.remove());
      return chip;
    }

    function hasChip(container, id){
      return !!container.querySelector('[data-id="'+id+'"]');
    }

    // Init from server-side selected arrays
    const selGenres = @json($selGenres);
    const selTags = @json($selTags);
    // Map lookups for labels
    const genreMap = {
      @foreach($genres as $g)
        {{ $g->id }}: '{{ addslashes($g->name) }}',
      @endforeach
    };
    const tagMap = {
      @foreach($tags as $t)
        {{ $t->id }}: '#{{ addslashes($t->name) }}',
      @endforeach
    };
    selGenres.forEach(id=>{ if(genreMap[id]) genreChips.appendChild(makeChip(id, genreMap[id], 'genre_ids')); });
    selTags.forEach(id=>{ if(tagMap[id]) tagChips.appendChild(makeChip(id, tagMap[id], 'tag_ids')); });

    // Filter genre options by discipline
    function filterGenres(){
      const disc = discSel?.value || '';
      [...genrePicker.options].forEach((opt, idx)=>{
        if (idx === 0) return; // placeholder
        const ok = !disc || opt.dataset.disc === disc;
        opt.hidden = !ok;
      });
    }
    discSel?.addEventListener('change', filterGenres);
    filterGenres();

    addGenreBtn?.addEventListener('click', ()=>{
      const opt = genrePicker.selectedOptions[0];
      if(!opt || !opt.value) return;
      if(!hasChip(genreChips, opt.value)) genreChips.appendChild(makeChip(opt.value, opt.textContent.trim(), 'genre_ids'));
    });
    addTagBtn?.addEventListener('click', ()=>{
      const opt = tagPicker.selectedOptions[0];
      if(!opt || !opt.value) return;
      if(!hasChip(tagChips, opt.value)) tagChips.appendChild(makeChip(opt.value, opt.textContent.trim(), 'tag_ids'));
    });
  })();
</script>
@endpush
@endsection

@push('scripts')
<script>
    // 장르 목록을 선택된 분야에 맞춰 필터
    (function(){
      const disc = document.getElementById('discipline_id');
      const genreSel = document.getElementById('genre_ids');
      if (!disc || !genreSel) return;
      const all = Array.from(genreSel.options);
      function applyFilter(){
        const val = disc.value || '';
        let anyShown = false;
        all.forEach(opt => {
          const show = !val || !opt.dataset.disc || opt.dataset.disc === val;
          opt.hidden = !show;
          if (!show) opt.selected = false;
          if (show) anyShown = true;
        });
        // size hint
        genreSel.size = Math.max(4, Math.min(10, all.filter(o=>!o.hidden).length));
      }
      disc.addEventListener('change', applyFilter);
      applyFilter();
    })();

    function addFeeRow() {
        const tbody = document.querySelector('#feeTable tbody');
        const idx = tbody.querySelectorAll('tr').length;
        const tpl = `
  <tr>
    <td><input name="fees[${idx}][currency]" value="KRW" maxlength="3" style="width:70px"></td>
    <td>
      <select name="fees[${idx}][unit]">
        <option value="appearance">출연</option>
        <option value="set">세트</option>
        <option value="hour">시간</option>
        <option value="day">일</option>
      </select>
    </td>
    <td><input name="fees[${idx}][region_code]" placeholder="KR, US-CA" style="width:100px"></td>
    <td><input type="number" name="fees[${idx}][min_fee]" min="0" style="width:120px"></td>
    <td><input type="number" name="fees[${idx}][max_fee]" min="0" style="width:120px"></td>
    <td><input name="fees[${idx}][notes]" style="width:160px"></td>
    <td style="text-align:center;"><input type="checkbox" name="fees[${idx}][is_active]" value="1" checked></td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeFeeRow(this)">삭제</button></td>
  </tr>`;
        tbody.insertAdjacentHTML('beforeend', tpl);
    }

    function removeFeeRow(btn) {
        const tr = btn.closest('tr');
        tr.parentNode.removeChild(tr);
    }
</script>
@endpush
