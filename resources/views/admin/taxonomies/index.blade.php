@extends('layouts.admin')

@section('content')
<h1>분류 관리 (분야/장르/태그)</h1>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div style="display:grid; grid-template-columns: 1fr; gap: 16px;">

    {{-- 분야 --}}
    <section style="border:1px solid var(--border); border-radius:8px; padding:12px; background: var(--surface); box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);">
        <h2>분야(Discipline)</h2>
        <form method="post" action="{{ route('admin.taxonomies.store') }}" style="display:flex; gap:8px; align-items:flex-end;">
            @csrf
            <input type="hidden" name="type" value="discipline">
            <div><label>이름<br><input name="name" required></label></div>
            <div><label>슬러그<br><input name="slug" required placeholder="music, mc, dance"></label></div>
            <button class="btn btn-primary" type="submit">추가</button>
        </form>
        <table class="table" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>슬러그</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($disciplines as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'discipline','id'=>$d->id]) }}" style="display:flex; gap:8px;">
                            @csrf @method('put')
                            <input name="name" value="{{ $d->name }}" required>
                            <input name="slug" value="{{ $d->slug }}" required>
                            <button class="btn btn-sm">저장</button>
                        </form>
                    </td>
                    <td>{{ $d->slug }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'discipline','id'=>$d->id]) }}" onsubmit="return confirm('삭제할까요?')" style="display:inline;">
                            @csrf @method('delete')
                            <button class="btn btn-sm btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    {{-- 장르 --}}
    <section style="border:1px solid var(--border); border-radius:8px; padding:12px; background: var(--surface); box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);">
        <h2>장르(Genre)</h2>
        <form method="post" action="{{ route('admin.taxonomies.store') }}" style="display:flex; gap:8px; align-items:flex-end; flex-wrap:wrap;">
            @csrf
            <input type="hidden" name="type" value="genre">
            <div><label>이름<br><input name="name" required></label></div>
            <div><label>슬러그<br><input name="slug" required placeholder="kpop, hiphop, ..."></label></div>
            <div><label>분야<br>
                    <select name="discipline_id">
                        <option value="">(선택)</option>
                        @foreach($disciplines as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                    </select></label>
            </div>
            <button class="btn btn-primary" type="submit">추가</button>
        </form>
        <table class="table" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>슬러그</th>
                    <th>분야</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($genres as $g)
                <tr>
                    <td>{{ $g->id }}</td>
                    <td colspan="2">
                        <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'genre','id'=>$g->id]) }}" style="display:flex; gap:8px; flex-wrap:wrap;">
                            @csrf @method('put')
                            <input name="name" value="{{ $g->name }}" required>
                            <input name="slug" value="{{ $g->slug }}" required>
                            <select name="discipline_id">
                                <option value="">(선택)</option>
                                @foreach($disciplines as $d)<option value="{{ $d->id }}" @selected($g->discipline_id==$d->id)>{{ $d->name }}</option>@endforeach
                            </select>
                            <button class="btn btn-sm">저장</button>
                        </form>
                    </td>
                    <td>{{ $g->discipline->name ?? '-' }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'genre','id'=>$g->id]) }}" onsubmit="return confirm('삭제할까요?')" style="display:inline;">
                            @csrf @method('delete')
                            <button class="btn btn-sm btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    {{-- 태그 --}}
    <section style="border:1px solid var(--border); border-radius:8px; padding:12px; background: var(--surface); box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);">
        <h2>태그(Tag)</h2>
        <form method="post" action="{{ route('admin.taxonomies.store') }}" style="display:flex; gap:8px; align-items:flex-end;">
            @csrf
            <input type="hidden" name="type" value="tag">
            <div><label>이름<br><input name="name" required></label></div>
            <div><label>슬러그<br><input name="slug" required placeholder="tv-popular, viral, ..."></label></div>
            <button class="btn btn-primary" type="submit">추가</button>
        </form>
        <table class="table" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>슬러그</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.taxonomies.update',['type'=>'tag','id'=>$t->id]) }}" style="display:flex; gap:8px;">
                            @csrf @method('put')
                            <input name="name" value="{{ $t->name }}" required>
                            <input name="slug" value="{{ $t->slug }}" required>
                            <button class="btn btn-sm">저장</button>
                        </form>
                    </td>
                    <td>{{ $t->slug }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.taxonomies.destroy',['type'=>'tag','id'=>$t->id]) }}" onsubmit="return confirm('삭제할까요?')" style="display:inline;">
                            @csrf @method('delete')
                            <button class="btn btn-sm btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

</div>
@endsection
