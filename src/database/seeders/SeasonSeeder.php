<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seasons')->insert([
            ['name' => '春'],
            ['name' => '夏'],
            ['name' => '秋'],
            ['name' => '冬'],
        ]);
    }
}
