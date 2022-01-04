<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'name' => 'football',
        ]);

        DB::table('tags')->insert([
            'name' => 'tennis',
        ]);

        DB::table('tags')->insert([
            'name' => 'news',
        ]);

        DB::table('tags')->insert([
            'name' => 'algeria',
        ]);

        DB::table('tags')->insert([
            'name' => 'bruh',
        ]);

    }
}
