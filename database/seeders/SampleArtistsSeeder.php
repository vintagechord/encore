<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class SampleArtistsSeeder extends Seeder
{
    public function run(): void
    {
        $genresByCat = [
            'music' => ['kpop','pop','rock','indie','jazz','hiphop','rnb'],
            'dance' => ['kpop','street','contemporary','ballet','traditional','cheer'],
            'mc'    => ['announcer','comedian','recreation'],
        ];

        $make = function(string $name, array $genres, int $min, int $max) {
            return [
                'stage_name' => $name,
                'min_fee' => $min,
                'max_fee' => $max,
                'genres' => $genres,
                'moods' => ['chill','energetic'][rand(0,1)],
                'formats' => ['solo','duo','live_band','dj'],
                'home_city' => 'Seoul',
                'active' => true,
            ];
        };

        $rows = [];
        foreach (range(1,20) as $i) {
            $rows[] = $make('MUSIC 팀 '.$i, [$genresByCat['music'][array_rand($genresByCat['music'])]], rand(800,2000)*1000, rand(2200,6000)*1000);
        }
        foreach (range(1,20) as $i) {
            $rows[] = $make('DANCE 팀 '.$i, [$genresByCat['dance'][array_rand($genresByCat['dance'])]], rand(600,1500)*1000, rand(1800,4200)*1000);
        }
        foreach (range(1,20) as $i) {
            $rows[] = $make('MC 진행자 '.$i, [$genresByCat['mc'][array_rand($genresByCat['mc'])]], rand(400,900)*1000, rand(900,2000)*1000);
        }

        foreach ($rows as $r) {
            Artist::create($r);
        }
    }
}

