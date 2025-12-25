<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class FakeOptionArtistsSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            1 => '보컬', 2 => '랩', 3 => 'DJ', 4 => '기타리스트', 5 => '베이시스트',
            6 => '드러머', 7 => '댄서', 8 => '안무가', 9 => 'MC', 10 => '사회자',
        ];

        $make = function(string $name, string $genre, int $min, int $max) {
            return [
                'stage_name' => $name,
                'min_fee' => $min,
                'max_fee' => $max,
                'genres' => [$genre],
                'active' => true,
                'home_city' => 'Seoul',
            ];
        };

        // 옵션1~3 각 10명씩
        foreach ([1=>'kpop', 2=>'hiphop', 3=>'pop'] as $opt => $genre) {
            foreach (range(1,10) as $i) {
                $role = $roles[$i] ?? '퍼포머';
                $name = "옵션{$opt}-아티스트 {$i} ({$role})";
                $min = rand(400,1200)*1000; $max = $min + rand(400,1800)*1000;
                Artist::create($make($name, $genre, $min, $max));
            }
        }
    }
}

