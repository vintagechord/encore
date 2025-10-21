<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['stage_name'=>'DJ LIME','min_fee'=>800000,'max_fee'=>1500000,'genres'=>['electronic','house'],'moods'=>['hype','party'],'formats'=>['dj']],
            ['stage_name'=>'Indigo Duo','min_fee'=>1200000,'max_fee'=>2200000,'genres'=>['indie','folk'],'moods'=>['chill','warm'],'formats'=>['duo','acoustic']],
            ['stage_name'=>'NEON BAND','min_fee'=>3000000,'max_fee'=>6000000,'genres'=>['rock','pop'],'moods'=>['energetic'],'formats'=>['live_band']],
            ['stage_name'=>'SOLO K','min_fee'=>900000,'max_fee'=>1800000,'genres'=>['rnb','pop'],'moods'=>['smooth','chill'],'formats'=>['solo','acoustic']],
        ];
        foreach ($rows as $r) Artist::create($r);
    }
}
