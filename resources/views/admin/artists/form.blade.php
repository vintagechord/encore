@extends('layouts.admin')

@section('content')
<h1>{{ $artist->exists ? '아티스트 수정' : '아티스트 생성' }}</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="post" action="{{ $artist->exists ? route('admin.artists.update',$artist) : route('admin.artists.store') }}">
    @csrf
    @if($artist->exists) @method('put') @endif

    {{-- 기본 정보 --}}
    <div style="display:grid; gap:10px; grid-template-columns: 1fr 1fr;">
        <div>
            <label>분야(Discipline)<br>
                <select name="discipline_id" required style="width:100%;">
                    @foreach($disciplines as $d)
                    <option value="{{ $d->id }}" @selected(old('discipline_id',$artist->discipline_id)==$d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </label>
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
    <div style="display:grid; gap:10px; grid-template-columns: 1fr 1fr; margin-top:12px;">
        <div>
            <label>장르(복수 선택 가능)<br>
                @php $selGenres = old('genre_ids', $artist->genresRelation->pluck('id')->all() ?? []); @endphp
                <select name="genre_ids[]" multiple size="6" style="width:100%; min-height:140px;">
                    @foreach($genres as $g)
                    <option value="{{ $g->id }}" @selected(in_array($g->id,$selGenres))>
                        {{ $g->name }} @if($g->discipline) ({{ $g->discipline->name }}) @endif
                    </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div>
            <label>태그(복수 선택 가능)<br>
                @php $selTags = old('tag_ids', $artist->tags->pluck('id')->all() ?? []); @endphp
                <select name="tag_ids[]" multiple size="6" style="width:100%; min-height:140px;">
                    @foreach($tags as $t)
                    <option value="{{ $t->id }}" @selected(in_array($t->id,$selTags))>#{{ $t->name }}</option>
                    @endforeach
                </select>
            </label>
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
        <button type="button" class="btn" onclick="addFeeRow()">+ 요율 추가</button>
    </fieldset>

    <div style="margin-top:12px;">
        <button class="btn btn-primary" type="submit">{{ $artist->exists ? '저장' : '생성' }}</button>
        <a class="btn" href="{{ route('admin.artists.index') }}">목록</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
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
