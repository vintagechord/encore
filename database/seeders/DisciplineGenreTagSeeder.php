<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Discipline;
use App\Models\Genre;
use App\Models\Tag;

class DisciplineGenreTagSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            ['slug' => 'music', 'name' => '음악 공연'],
            ['slug' => 'mc',    'name' => '사회(MC)'],
            ['slug' => 'dance', 'name' => '댄스'],
            ['slug' => 'other', 'name' => '기타'],
        ];
        foreach ($disciplines as $d) {
            Discipline::firstOrCreate(['slug' => $d['slug']], $d);
        }

        $genres = [
            ['discipline' => 'music', 'slug' => 'kpop', 'name' => 'K-POP'],
            ['discipline' => 'music', 'slug' => 'hiphop', 'name' => '힙합'],
            ['discipline' => 'music', 'slug' => 'rnb', 'name' => 'R&B'],
            ['discipline' => 'music', 'slug' => 'electronic', 'name' => '일렉트로닉'],
            ['discipline' => 'music', 'slug' => 'rock', 'name' => '록/메탈'],
            ['discipline' => 'music', 'slug' => 'indie', 'name' => '인디'],
            ['discipline' => 'music', 'slug' => 'jazz', 'name' => '재즈'],
        ];
        foreach ($genres as $g) {
            $disc = Discipline::where('slug', $g['discipline'])->first();
            Genre::firstOrCreate(['slug' => $g['slug']], [
                'name' => $g['name'],
                'discipline_id' => $disc?->id,
            ]);
        }

        $tags = ['tv-popular' => '방송출연다수', 'viral' => '바이럴', 'award-winning' => '수상', 'rookie' => '루키', 'legend' => '레전드'];
        foreach ($tags as $slug => $name) {
            Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
