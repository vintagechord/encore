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
        $per = (int) $request->query('per', 20);
        if (!in_array($per, [20,50,100], true)) $per = 20;

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
            ->paginate($per)
            ->appends($request->query());

        return view('admin.artists.index', [
            'artists' => $artists,
            'disciplines' => Discipline::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'filters' => $filters + ['per' => $per],
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

        // 이미지 업로드 처리
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $artist->update(['image_path' => $path]);
        }

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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $artist->update(['image_path' => $path]);
        }

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

    /**
     * 샘플 아티스트를 분야별로 최대 {per}명까지 채웁니다.
     * 기본값 100명/분야. 이미 존재하는 수를 제외하고 부족분만 생성합니다.
     */
    public function seed(Request $request)
    {
        $per = max(1, min(500, (int)$request->input('per', 100)));

        // 보조: 기본 분류가 비어있다면 music/mc/dance를 생성
        if (Discipline::count() === 0) {
            Discipline::firstOrCreate(['slug' => 'music'], ['name' => '음악']);
            Discipline::firstOrCreate(['slug' => 'mc'], ['name' => '사회(MC)']);
            Discipline::firstOrCreate(['slug' => 'dance'], ['name' => '댄스']);
        }

        $disciplines = Discipline::orderBy('id')->get();

        // 최소 장르 세트 보장(글로벌 유니크 slug 충돌 방지: 분야 접두어 적용)
        foreach ($disciplines as $d) {
            $hasGenre = Genre::where('discipline_id', $d->id)->exists();
            if (!$hasGenre) {
                $defaults = match($d->slug) {
                    'mc'    => [['slug'=>'event','name'=>'행사'], ['slug'=>'wedding','name'=>'웨딩'], ['slug'=>'tv','name'=>'방송']],
                    'dance' => [['slug'=>'kpop','name'=>'K-POP'], ['slug'=>'hiphop','name'=>'힙합'], ['slug'=>'modern','name'=>'현대무용']],
                    default => [['slug'=>'kpop','name'=>'K-POP'], ['slug'=>'rnb','name'=>'R&B'], ['slug'=>'rock','name'=>'록'], ['slug'=>'indie','name'=>'인디']],
                };
                foreach ($defaults as $g) {
                    $slug = $d->slug . '-' . $g['slug'];
                    Genre::firstOrCreate(['slug'=>$slug], ['discipline_id'=>$d->id, 'name'=>$g['name']]);
                }
            }
        }

        $adj = ['블루','네온','브라이트','다크','골든','실버','루미너스','어반','클래식','퓨처','다이나믹','라이트','미러','플라넷','스타','루프','레트로','바이브','프리즘','시그널'];
        $nounsBand = ['밴드','듀오','트리오','프로젝트','앙상블','오케스트라','콰르텟'];
        $nounsDance = ['댄스','크루','댄스팀','퍼포먼스'];
        $namesMc = ['하모니','시그널','제이','케이','민트','보라','스톤','샤인','웨이브','스텝','보이스','라임','루비','에코','노바','아이리스','델타','새턴','포커스','스카이'];

        $created = 0;
        foreach ($disciplines as $d) {
            $current = Artist::where('discipline_id', $d->id)->count();
            $need = max(0, $per - $current);
            if ($need === 0) continue;

            // 장르 목록(필요 시 기본값 생성: 글로벌 유니크(slug) 충돌을 피하기 위해 분야 접두어를 붙임)
            $genList = Genre::where('discipline_id', $d->id)->pluck('id')->all();
            if (count($genList) === 0) {
                $baseDefaults = match($d->slug) {
                    'mc'    => [['slug'=>'event','name'=>'행사'], ['slug'=>'wedding','name'=>'웨딩'], ['slug'=>'tv','name'=>'방송']],
                    'dance' => [['slug'=>'kpop','name'=>'K-POP'], ['slug'=>'hiphop','name'=>'힙합'], ['slug'=>'modern','name'=>'현대무용']],
                    default => [['slug'=>'kpop','name'=>'K-POP'], ['slug'=>'rnb','name'=>'R&B'], ['slug'=>'rock','name'=>'록'], ['slug'=>'indie','name'=>'인디']],
                };
                foreach ($baseDefaults as $g) {
                    $slug = $d->slug . '-' . $g['slug'];
                    $row = Genre::firstOrCreate(['slug'=>$slug], ['discipline_id'=>$d->id, 'name'=>$g['name']]);
                }
                $genList = Genre::where('discipline_id', $d->id)->pluck('id')->all();
            }
            for ($i=0; $i<$need; $i++) {
                // 이름 생성
                if ($d->slug === 'mc') {
                    $nm = 'MC ' . $namesMc[array_rand($namesMc)] . ' ' . rand(1,99);
                } elseif ($d->slug === 'dance') {
                    $nm = $adj[array_rand($adj)] . ' ' . $nounsDance[array_rand($nounsDance)];
                } else {
                    $nm = $adj[array_rand($adj)] . ' ' . $nounsBand[array_rand($nounsBand)];
                }

                // 요율 범위(대략적)
                $baseMin = match($d->slug) {
                    'mc'    => rand(300, 1200) * 1000,
                    'dance' => rand(400, 1500) * 1000,
                    default => rand(800, 5000) * 1000,
                };
                $baseMax = $baseMin + rand(200, 3000) * 1000;

                $artist = Artist::create([
                    'discipline_id' => $d->id,
                    'stage_name'    => $nm,
                    'fame_score'    => rand(20, 90),
                    'active'        => true,
                ]);

                // 장르 연결(1~3개)
                if ($genList) {
                    $pick = collect($genList)->shuffle()->take(rand(1, min(3, count($genList))))->all();
                    $artist->genresRelation()->sync($pick);
                }

                // 요율 생성
                $artist->fees()->create([
                    'currency'  => 'KRW',
                    'min_fee'   => $baseMin,
                    'max_fee'   => $baseMax,
                    'unit'      => 'appearance',
                    'is_active' => true,
                ]);

                $created++;
            }
        }

        return back()->with('ok', "샘플 아티스트를 {$created}명 생성했습니다.");
    }

    /**
     * 실제 존재하는 팀/아티스트/MC 중심의 고정 목록으로 교체/보충합니다.
     * - 기본값: 모든 기존 아티스트 삭제 후 분류별 20명씩 생성(replace=1)
     * - replace=0 이면 기존은 유지하고 모자란 분만 채웁니다.
     */
    public function seedReal(Request $request)
    {
        $replace = (bool)$request->input('replace', 1);

        // 보조: 필수 분류 보장
        $music = Discipline::firstOrCreate(['slug' => 'music'], ['name' => '음악']);
        $mc    = Discipline::firstOrCreate(['slug' => 'mc'],    ['name' => '사회(MC)']);
        $dance = Discipline::firstOrCreate(['slug' => 'dance'], ['name' => '댄스']);
        $speak = Discipline::firstOrCreate(['slug' => 'speaker'], ['name' => '강연']);

        // 장르 프리셋(분야 접두어 slug 유지)
        $ensureGenre = function(Discipline $d, array $list){
            foreach ($list as $slug => $name) {
                Genre::firstOrCreate(['slug'=>$d->slug.'-'.$slug], [
                    'discipline_id'=>$d->id,
                    'name'=>$name,
                ]);
            }
        };
        $ensureGenre($music, ['kpop'=>'K-POP','rnb'=>'R&B','rock'=>'록','indie'=>'인디','ballad'=>'발라드','hiphop'=>'힙합']);
        $ensureGenre($mc,    ['event'=>'행사','tv'=>'방송','wedding'=>'웨딩']);
        $ensureGenre($dance, ['kpop'=>'K-POP','hiphop'=>'힙합','modern'=>'현대무용','bboy'=>'비보이']);
        $ensureGenre($speak, ['talk'=>'특강','tech'=>'테크','leadership'=>'리더십']);

        // 고정 목록(실제 존재 팀/아티스트/MC/댄스팀 일부 예시)
        $list = [
            'music' => [
                'BTS','BLACKPINK','SEVENTEEN','EXO','TWICE','Red Velvet','(G)I-DLE','NewJeans','ITZY','Stray Kids',
                'ENHYPEN','NCT 127','NCT DREAM','BIGBANG','AKMU','IU','ZICO','PSY','aespa','LE SSERAFIM',
            ],
            'mc' => [
                '유재석','강호동','신동엽','전현무','김성주','박나래','붐','장성규','김구라','서장훈',
                '김원효','이휘재','문세윤','노홍철','도경완','성시경','이수근','이영자','김제동','송은이',
            ],
            'dance' => [
                'JUST JERK','1MILLION Dance','Jinjo Crew','Gamblerz Crew','Ambiguous Dance Company','HolyBang','HOOK','PROWDMON','WAYB','LACHICA',
                'CocaNButter','YGX','WANT','Mbitious','Prime Kingz','Deep N DAP','K-Da Crew','Fusion MC','Korea National Contemporary Dance Company','Last For One',
            ],
            'speaker' => [
                '김미경','최재천','정재승','유발 하라리','말콤 글래드웰','사이먼 시넥','댄 애리얼리','워런 버핏','스티브 워즈니악','빌 게이츠',
                '제인 구달','팀 버너스리','셰릴 샌드버그','안철수','박지성','한비야','니콜라스 카','에이미 커디','에스더 다이슨','에릭 슈미트',
            ],
        ];

        if ($replace) {
            // 외래키 제약 고려: 소프트 삭제/연쇄 삭제가 없으면 개별 삭제
            foreach (\App\Models\Artist::cursor() as $old) { $old->delete(); }
        }

        $created = 0;
        $map = [ 'music'=>$music, 'mc'=>$mc, 'dance'=>$dance, 'speaker'=>$speak ];
        foreach ($list as $key => $names) {
            $d = $map[$key];
            $existing = Artist::where('discipline_id',$d->id)->pluck('stage_name')->all();
            $need = array_slice($names, 0, 20);
            foreach ($need as $nm) {
                if (in_array($nm, $existing, true)) continue;
                $artist = Artist::create([
                    'discipline_id' => $d->id,
                    'stage_name'    => $nm,
                    'fame_score'    => 80, // 실존 유명 인물 가정
                    'active'        => true,
                ]);

                // 분야별 대표 장르 연결
                $gids = Genre::where('discipline_id',$d->id)->pluck('id')->all();
                if ($gids) $artist->genresRelation()->sync(collect($gids)->shuffle()->take(2)->all());

                // 분야별 요율 대략값
                [$min,$max] = match($key) {
                    'mc'      => [1500000, 5000000],
                    'dance'   => [2000000, 6000000],
                    'speaker' => [3000000, 10000000],
                    default   => [5000000, 20000000],
                };
                $artist->fees()->create([
                    'currency'=>'KRW','min_fee'=>$min,'max_fee'=>$max,'unit'=>'appearance','is_active'=>true
                ]);
                $created++;
            }
        }

        return back()->with('ok', "실존 아티스트/팀/강연자 {$created}명을 배치했습니다.")->withInput();
    }

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
            'image'         => ['nullable', 'image', 'max:4096'],
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

    /** CSV 업로드 폼 */
    public function importForm()
    {
        return view('admin.artists.import');
    }

    /** CSV 업로드 처리
     * 헤더 예시:
     * stage_name,discipline,genres,tags,fee_min,fee_max,unit,fame_score,is_active,notes
     * 장르는 쉼표(,) 또는 파이프(|) 구분. discipline: music|mc|dance|speaker (한글도 허용: 음악, 사회, 댄스, 강연)
     */
    public function import(Request $request)
    {
        $data = $request->validate([
            'file'    => ['required','file','mimes:csv,txt'],
            'replace' => ['nullable','boolean'],
        ]);

        if ($request->boolean('replace')) {
            foreach (\App\Models\Artist::cursor() as $old) { $old->delete(); }
        }

        $file = $request->file('file');
        $fp = fopen($file->getRealPath(), 'r');
        if (!$fp) return back()->withErrors(['file'=>'파일을 열 수 없습니다.']);

        $header = null; $rowNo = 0; $created = 0; $updated = 0; $errors = 0;
        $normDiscipline = function($v){
            $v = trim(mb_strtolower((string)$v,'UTF-8'));
            $map = ['음악'=>'music','music'=>'music','뮤지션'=>'music','가수'=>'music',
                    'mc'=>'mc','사회'=>'mc','아나운서'=>'mc',
                    '댄스'=>'dance','dance'=>'dance','무용'=>'dance',
                    '강연'=>'speaker','강사'=>'speaker','speaker'=>'speaker'];
            return $map[$v] ?? 'music';
        };
        $ensureDiscipline = function($slug) {
            return Discipline::firstOrCreate(['slug'=>$slug], ['name'=> match($slug){ 'mc'=>'사회(MC)','dance'=>'댄스','speaker'=>'강연', default=>'음악'}]);
        };
        $ensureGenre = function($disciplineId, $disciplineSlug, $nameSlugOrName) {
            $nameSlugOrName = (string)$nameSlugOrName;
            if ($nameSlugOrName==='') return null;
            $slug = $disciplineSlug.'-'.Str::slug($nameSlugOrName);
            $name = $nameSlugOrName;
            return Genre::firstOrCreate(['slug'=>$slug], ['discipline_id'=>$disciplineId, 'name'=>$name]);
        };

        while (($row = fgetcsv($fp)) !== false) {
            $rowNo++;
            if ($rowNo === 1) { $header = array_map(fn($x)=>trim($x), $row); continue; }
            if (!$header) break;
            $assoc = [];
            foreach ($row as $i=>$val) { $assoc[$header[$i] ?? $i] = trim($val); }
            $name = $assoc['stage_name'] ?? '';
            if ($name==='') { $errors++; continue; }
            $discSlug = $normDiscipline($assoc['discipline'] ?? 'music');
            $disc = $ensureDiscipline($discSlug);

            $artist = Artist::firstOrNew(['stage_name'=>$name, 'discipline_id'=>$disc->id]);
            $isNew = !$artist->exists;
            $artist->fill([
                'discipline_id' => $disc->id,
                'stage_name'    => $name,
                'fame_score'    => (int)($assoc['fame_score'] ?? ($artist->fame_score ?? 50)),
                'active'        => ($assoc['is_active'] ?? '1') ? true : false,
                'notes'         => $assoc['notes'] ?? ($artist->notes ?? null),
            ]);
            $artist->save();

            // 장르 연결
            $genresRaw = $assoc['genres'] ?? '';
            if ($genresRaw !== '') {
                $parts = preg_split('/[|,]+/u', $genresRaw, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                $gids = [];
                foreach ($parts as $g) {
                    $gr = $ensureGenre($disc->id, $disc->slug, $g);
                    if ($gr) $gids[] = $gr->id;
                }
                if ($gids) $artist->genresRelation()->sync($gids);
            }

            // 태그 연결(선택)
            if (!empty($assoc['tags'])) {
                $tids=[]; $parts = preg_split('/[|,]+/u', $assoc['tags'], -1, PREG_SPLIT_NO_EMPTY) ?: [];
                foreach ($parts as $t) { $tids[] = Tag::firstOrCreate(['name'=>trim($t)])->id; }
                if ($tids) $artist->tags()->sync($tids);
            }

            // 요율 업데이트/생성
            $min = $assoc['fee_min'] !== '' ? (int)$assoc['fee_min'] : null;
            $max = $assoc['fee_max'] !== '' ? (int)$assoc['fee_max'] : null;
            if ($min !== null || $max !== null) {
                $artist->fees()->updateOrCreate(['is_active'=>true], [
                    'currency' => 'KRW',
                    'min_fee'  => $min,
                    'max_fee'  => $max,
                    'unit'     => $assoc['unit'] ?: 'appearance',
                    'is_active'=> true,
                ]);
            }

            $isNew ? $created++ : $updated++;
        }
        fclose($fp);

        return redirect()->route('admin.artists.index')
            ->with('ok', "CSV 처리 완료: 생성 {$created}건, 갱신 {$updated}건, 오류 {$errors}건");
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
