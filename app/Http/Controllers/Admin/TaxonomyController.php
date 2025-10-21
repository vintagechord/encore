<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxonomyController extends Controller
{
    public function index()
    {
        return view('admin.taxonomies.index', [
            'disciplines' => Discipline::orderBy('name')->get(),
            'genres'      => Genre::with('discipline')->orderBy('name')->get(),
            'tags'        => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $type = $request->string('type')->toString(); // discipline|genre|tag
        if ($type === 'discipline') {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', 'unique:disciplines,slug'],
            ]);
            Discipline::create($data);
        } elseif ($type === 'genre') {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', 'unique:genres,slug'],
                'discipline_id' => ['nullable', 'exists:disciplines,id'],
            ]);
            Genre::create($data);
        } elseif ($type === 'tag') {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', 'unique:tags,slug'],
            ]);
            Tag::create($data);
        }
        return back()->with('ok', '추가되었습니다.');
    }

    public function update(Request $request, string $type, int $id)
    {
        if ($type === 'discipline') {
            $d = Discipline::findOrFail($id);
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', Rule::unique('disciplines', 'slug')->ignore($d->id)],
            ]);
            $d->update($data);
        } elseif ($type === 'genre') {
            $g = Genre::findOrFail($id);
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', Rule::unique('genres', 'slug')->ignore($g->id)],
                'discipline_id' => ['nullable', 'exists:disciplines,id'],
            ]);
            $g->update($data);
        } elseif ($type === 'tag') {
            $t = Tag::findOrFail($id);
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', Rule::unique('tags', 'slug')->ignore($t->id)],
            ]);
            $t->update($data);
        }
        return back()->with('ok', '수정되었습니다.');
    }

    public function destroy(string $type, int $id)
    {
        if ($type === 'discipline') Discipline::findOrFail($id)->delete();
        elseif ($type === 'genre')  Genre::findOrFail($id)->delete();
        elseif ($type === 'tag')    Tag::findOrFail($id)->delete();

        return back()->with('ok', '삭제했습니다.');
    }
}
