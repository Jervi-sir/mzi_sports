<?php

namespace Database\Seeders;

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
            'media_link' => 'bruh.com',
            'media' => 'pics/card1.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'type' => 'image',
            'media_link' => 'bruh.com',
            'media' => 'pics/card2.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'type' => 'video',
            'media_link' => 'bruh.com',
            'media' => 'pics/card3.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 3,
            'type' => 'image',
            'media_link' => 'bruh.com',
            'media' => 'pics/card4.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

        DB::table('posts')->insert([
            'user_id' => 3,
            'type' => 'image',
            'media_link' => 'bruh.com',
            'media' => 'pics/card5.png',
            'description' => Str::random(40),
            'tags' => 'zzz, zzd, zfdsw',
            'others' => Str::random(40),
        ]);

    }
}
