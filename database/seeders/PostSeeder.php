<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'user_id' => 1,
            'type' => 'video',
            'media_link' => Helper::random(),
            'media' => 'pics/card1.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'type' => 'image',
            'media_link' => Helper::random(),
            'media' => 'pics/card2.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'type' => 'video',
            'media_link' => Helper::random(),
            'media' => 'pics/card3.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 3,
            'type' => 'image',
            'media_link' => Helper::random(),
            'media' => 'pics/card4.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 3,
            'type' => 'image',
            'media_link' => Helper::random(),
            'media' => 'pics/card5.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

    }
}
