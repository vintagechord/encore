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
    /**
     * Seed default disciplines/genres/tags when empty so admin forms have choices.
     */
    public function seedDefaults() {
        // 기본 대분류(디icipline)
        $discMap = [
            'music'       => '음악',
            'dance'       => '댄스',
            'performance' => '퍼포먼스',
            'mc'          => '사회(MC)',
            'planned'     => '기획공연',
            'celebrity'   => '셀럽',
            'foreign'     => '외국인',
        ];

        // 각 분류별 세부 카테고리(Genre)
        $genreMap = [
            'music' => [
                // 대중음악 계열
                'jazz' => '재즈', 'indie' => '인디', 'band' => '밴드', 'acoustic' => '어쿠스틱밴드',
                'dj_hiphop' => 'DJ/힙합/비트박스', 'kpop' => 'K-POP/대중음악', 'newage_world' => '뉴에이지/월드뮤직',
                'electronic' => '전자현악', 'musical' => '뮤지컬', 'popera' => '팝페라', 'acapella' => '아카펠라',
                '7080' => '7080/통기타', 'mochang' => '모창', 'trot' => '트로트', 'ballad' => '발라드', 'rock' => '록/메탈', 'pop' => '팝',
                // 클래식 계열(음악 하위로 이관)
                'orchestra' => '오케스트라', 'soloist' => '솔리스트', 'ensemble' => '앙상블', 'vocal' => '성악', 'opera' => '오페라', 'choir' => '합창단',
                // 전통 계열(음악 하위로 이관)
                'gugak_orch' => '국악관현악단', 'master' => '명인/명창', 'fusion' => '퓨전국악', 'dance_trad' => '전통무용',
                'yeonhui' => '전통연희', 'pumba' => '품바/마당극', 'minyo' => '민요/판소리/전통음악',
            ],
            'dance' => [
                'kpop_dance' => 'K-POP댄스', 'bboy' => '비보이', 'belly' => '밸리댄스', 'sports' => '댄스스포츠',
                'cheer' => '치어리더', 'creative' => '창작무용', 'ballet_contemp_jazz' => '발레/현대무용/재즈',
            ],
            'performance' => [
                'percussive' => '타악퍼포먼스', 'brass' => '브라스 퍼포먼스', 'magic' => '마술쇼',
                'mime_juggle_bubble_clown' => '마임/저글링/버블/삐에로', 'drawing_sand' => '드로잉쇼/샌드애니메이션',
                'martial' => '무술 퍼포먼스', 'laser_led' => '레이저/LED 퍼포먼스', 'brush' => '붓글씨 퍼포먼스',
                'media' => '미디어 퍼포먼스', 'robot' => '로봇', 'caricature_face' => '캐리커쳐/페이스페인팅',
                'number' => '넘버벌 퍼포먼스', 'circus' => '서커스', 'parade' => '퍼레이드/마칭',
                'cocktail' => '칵테일 쇼', 'fire' => '불쇼',
            ],
            // MC는 세부 역할을 장르로 취급
            'mc' => [
                'announcer' => '아나운서', 'professional' => '전문 MC', 'mc_en' => '영어 MC', 'mc_zh' => '중국어 MC', 'mc_ja' => '일본어 MC', 'comedian' => '개그MC',
            ],
            'planned' => [
                'convergence' => '융복합 공연', 'north_korea' => '북한예술단', 'gag' => '개그공연', 'foreign_troupe' => '외국인 공연단', 'theatre' => '극공연',
                'kids' => '어린이 공연', 'kids_singalong' => '어린이 싱어롱쇼', 'etc' => '기타',
            ],
            'celebrity' => [
                'mentor' => '명사', 'expert' => '전문강사', 'broadcaster' => '방송인', 'professor' => '교수', 'chef' => '셰프', 'health' => '헬스',
                'model' => '모델', 'beauty' => '뷰티', 'business' => '기업인', 'sports' => '스포츠', 'religion' => '종교인', 'creator' => '크리에이터(유튜버/BJ)',
            ],
            'foreign' => [
                'china' => '중국', 'japan' => '일본', 'usa' => '미국', 'se_asia' => '동남아시아', 'etc' => '기타공연',
            ],
        ];

        $created = ['disciplines' => 0, 'genres' => 0, 'tags' => 0];

        foreach ($discMap as $slug => $name) {
            $disc = \App\Models\Discipline::firstOrCreate(['slug' => $slug], ['name' => $name]);
            if ($disc->wasRecentlyCreated) $created['disciplines']++;

            foreach ($genreMap[$slug] as $gslug => $gname) {
                // If this discipline already has the genre by name, skip
                if (\App\Models\Genre::where('discipline_id', $disc->id)->where('name', $gname)->exists()) {
                    continue;
                }
                // Ensure slug is globally unique (genres.slug has unique index)
                $candidate = $gslug;
                if (\App\Models\Genre::where('slug', $candidate)->exists()) {
                    $candidate = $slug . '_' . $gslug; // prefix with discipline slug
                    $n = 2;
                    while (\App\Models\Genre::where('slug', $candidate)->exists()) {
                        $candidate = $slug . '_' . $gslug . '_' . $n++;
                    }
                }
                $g = \App\Models\Genre::firstOrCreate(
                    ['slug' => $candidate, 'discipline_id' => $disc->id],
                    ['name' => $gname]
                );
                if ($g->wasRecentlyCreated) $created['genres']++;
            }
        }

        // ===== Default Tags (approx. 50 common moods/usages)
        $tags = [
            'bright' => '밝은',
            'dark' => '어두운',
            'energetic' => '에너지',
            'upbeat' => '신나는',
            'calm' => '차분한',
            'emotional' => '감성적인',
            'romantic' => '로맨틱',
            'dreamy' => '몽환적인',
            'groovy' => '그루비',
            'catchy' => '중독성',
            'intense' => '강렬한',
            'powerful' => '파워풀',
            'lyrical' => '서정적인',
            'hopeful' => '희망찬',
            'warm' => '따뜻한',
            'lonely' => '쓸쓸한',
            'melancholic' => '우울한',
            'tense' => '긴장감',
            'dramatic' => '드라마틱',
            'suspense' => '서스펜스',
            'peaceful' => '평온한',
            'laid-back' => '여유로운',
            'happy' => '행복한',
            'party' => '파티',
            'danceable' => '춤추기좋은',
            'driving' => '드라이빙',
            'workout' => '운동',
            'focus' => '집중',
            'study' => '공부',
            'sleep' => '수면',
            'morning' => '아침',
            'night' => '밤',
            'rainy' => '비오는날',
            'sunny' => '햇살',
            'travel' => '여행',
            'cafe' => '카페',
            'healing' => '힐링',
            'retro' => '레트로',
            '80s' => '80s',
            '90s' => '90s',
            '2000s' => '2000s',
            'feel-good' => '필굿',
            'cool' => '쿨한',
            'soulful' => '소울풀',
            'jazzy' => '재지',
            'bluesy' => '블루지',
            'acoustic' => '어쿠스틱',
            'electronic' => '일렉트로닉',
            'orchestral' => '오케스트라',
            'piano' => '피아노',
            'guitar' => '기타',
            'bass' => '베이스',
            'drums' => '드럼',
            'vocal' => '보컬',
            'chorus' => '코러스',
            'instrumental' => '인스트루멘탈',
            'live' => '라이브',
            'soundtrack' => 'OST/사운드트랙',
            'cover' => '커버',
            'remix' => '리믹스',
            'lofi' => '로파이',
            'ambient' => '엠비언트',
            'cinematic' => '시네마틱',
            'minimal' => '미니멀',
        ];
        foreach ($tags as $slug => $name) {
            // Avoid duplicates by slug or by existing same-name entries
            $exists = \App\Models\Tag::where('slug', $slug)->orWhere('name', $name)->exists();
            if ($exists) continue;
            \App\Models\Tag::create(['slug' => $slug, 'name' => $name]);
            $created['tags']++;
        }

        return back()->with('ok', sprintf('기본 분류를 채웠습니다. (분야 %d, 장르 %d, 태그 %d)', $created['disciplines'], $created['genres'], $created['tags']));
    }
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
