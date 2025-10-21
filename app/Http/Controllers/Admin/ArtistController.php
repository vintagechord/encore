<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtistFee;
use App\Models\Discipline;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->string('q')->toString(),
            'discipline_id' => $request->integer('discipline_id') ?: null,
            'min_fame' => $request->integer('min_fame') ?: null,
            'max_fame' => $request->integer('max_fame') ?: null,
            'genre_ids' => $request->input('genre_ids', []),
            'tag_ids' => $request->input('tag_ids', []),
            'is_active' => $request->filled('is_active') ? (bool)$request->input('is_active') : null,
        ];

        $artists = Artist::with(['discipline', 'genresRelation', 'tags', 'fees' => fn($q) => $q->orderBy('created_at', 'desc')])
            ->filter(array_filter($filters, fn($v) => $v !== null && $v !== ''))
            ->orderByDesc('fame_score')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.artists.index', [
            'artists' => $artists,
            'disciplines' => Discipline::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('admin.artists.form', [
            'artist' => new Artist(),
            'disciplines' => Discipline::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateBase($request);

        $artist = Artist::create([
            'discipline_id' => $data['discipline_id'],
            'stage_name'    => $data['stage_name'],
            'legal_name'    => $data['legal_name'] ?? null,
            'fame_score'    => $data['fame_score'] ?? 50,
            'active'        => $data['is_active'] ?? true,
            'external_links' => $data['external_links'] ?? null,
            'bio'           => $data['bio'] ?? null,
        ]);

        $artist->genresRelation()->sync($data['genre_ids'] ?? []);
        $artist->tags()->sync($data['tag_ids'] ?? []);

        $fees = $data['fees'] ?? ($data['fee'] ? [$data['fee']] : []);
        $this->syncFees($artist, $fees);

        return redirect()->route('admin.artists.index')->with('ok', '아티스트를 생성했습니다.');
    }

    public function edit(Artist $artist)
    {
        $artist->load(['genresRelation', 'tags', 'fees']);
        return view('admin.artists.form', [
            'artist' => $artist,
            'disciplines' => Discipline::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Artist $artist)
    {
        $data = $this->validateBase($request, $artist->id);

        $artist->update([
            'discipline_id' => $data['discipline_id'],
            'stage_name'    => $data['stage_name'],
            'legal_name'    => $data['legal_name'] ?? null,
            'fame_score'    => $data['fame_score'] ?? 50,
            'active'        => $data['is_active'] ?? true,
            'external_links' => $data['external_links'] ?? null,
            'bio'           => $data['bio'] ?? null,
        ]);

        $artist->genresRelation()->sync($data['genre_ids'] ?? []);
        $artist->tags()->sync($data['tag_ids'] ?? []);

        $fees = $data['fees'] ?? ($data['fee'] ? [$data['fee']] : []);
        $this->syncFees($artist, $fees);

        return redirect()->route('admin.artists.index')->with('ok', '아티스트를 수정했습니다.');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return back()->with('ok', '삭제했습니다.');
    }

    /* ----------------------- 내부 헬퍼 ----------------------- */

    private function validateBase(Request $request, ?int $id = null): array
    {
        $extLinks = $request->input('external_links');
        if (is_string($extLinks) && $extLinks !== '') {
            $decoded = json_decode($extLinks, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['external_links' => $decoded]);
            }
        }

        $base = $request->validate([
            'discipline_id' => ['required', 'exists:disciplines,id'],
            'stage_name'    => ['required', 'string', 'max:255'],
            'legal_name'    => ['nullable', 'string', 'max:255'],
            'fame_score'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active'     => ['nullable', 'boolean'],
            'external_links' => ['nullable', 'array'],
            'bio'           => ['nullable', 'string'],
            'genre_ids'     => ['nullable', 'array'],
            'genre_ids.*'   => ['integer', 'exists:genres,id'],
            'tag_ids'       => ['nullable', 'array'],
            'tag_ids.*'     => ['integer', 'exists:tags,id'],

            // 단일 입력(하위 호환)
            'fee.currency'   => ['nullable', 'string', 'size:3'],
            'fee.min_fee'    => ['nullable', 'integer', 'min:0'],
            'fee.max_fee'    => ['nullable', 'integer', 'min:0'],
            'fee.unit'       => ['nullable', Rule::in(['appearance', 'set', 'hour', 'day'])],
            'fee.region_code' => ['nullable', 'string', 'max:8'],
            'fee.notes'      => ['nullable', 'string', 'max:2000'],

            // 다중 요율
            'fees'                 => ['nullable', 'array'],
            'fees.*.id'            => ['nullable', 'integer', 'exists:artist_fees,id'],
            'fees.*.currency'      => ['required_with:fees', 'string', 'size:3'],
            'fees.*.min_fee'       => ['nullable', 'integer', 'min:0'],
            'fees.*.max_fee'       => ['nullable', 'integer', 'min:0'],
            'fees.*.unit'          => ['nullable', Rule::in(['appearance', 'set', 'hour', 'day'])],
            'fees.*.region_code'   => ['nullable', 'string', 'max:8'],
            'fees.*.notes'         => ['nullable', 'string', 'max:2000'],
            'fees.*.is_active'     => ['nullable', 'boolean'],
        ]);

        return $base;
    }

    /**
     * 다중 요율 동기화: 전달된 배열 기준으로 생성/수정/삭제 반영
     */
    private function syncFees(Artist $artist, array $fees): void
    {
        // 현재 보유 fee id 목록
        $existing = $artist->fees()->pluck('id')->all();
        $keepIds = [];

        foreach ($fees as $row) {
            if (!is_array($row)) continue;

            $payload = [
                'currency'   => $row['currency'] ?? 'KRW',
                'min_fee'    => $row['min_fee'] ?? null,
                'max_fee'    => $row['max_fee'] ?? null,
                'unit'       => $row['unit'] ?? 'appearance',
                'region_code' => $row['region_code'] ?? null,
                'notes'      => $row['notes'] ?? null,
                'is_active'  => array_key_exists('is_active', $row) ? (bool)$row['is_active'] : true,
            ];

            if (!empty($row['id']) && in_array((int)$row['id'], $existing, true)) {
                $artist->fees()->whereKey($row['id'])->update($payload);
                $keepIds[] = (int)$row['id'];
            } else {
                $new = $artist->fees()->create($payload);
                $keepIds[] = $new->id;
            }
        }

        // 전달되지 않은 나머지 fee 삭제
        $toDelete = array_diff($existing, $keepIds);
        if ($toDelete) $artist->fees()->whereIn('id', $toDelete)->delete();
    }
}
