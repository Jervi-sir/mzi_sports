<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post = new Post;
        $post->user_id = 1;
        $post->type = 'video';
        $post->media_link = 'bruh.com';
        $post->description = Str::random(40);
        $post->tags = 'zzz, zzd, zfdsw';
        $post->others = Str::random(40);
        $post->save();

        $post = new Post;
        $post->user_id = 1;
        $post->type = 'image';
        $post->media_link = 'bruh.com';
        $post->description = Str::random(40);
        $post->tags = 'zzz, zzd, zfdsw';
        $post->others = Str::random(40);
        $post->save();

        $post = new Post;
        $post->user_id = 2;
        $post->type = 'video';
        $post->media_link = 'bruh.com';
        $post->description = Str::random(40);
        $post->tags = 'zzz, zzd, zfdsw';
        $post->others = Str::random(40);
        $post->save();

        $post = new Post;
        $post->user_id = 3;
        $post->type = 'image';
        $post->media_link = 'bruh.com';
        $post->description = Str::random(40);
        $post->tags = 'zzz, zzd, zfdsw';
        $post->others = Str::random(40);
        $post->save();
    }
}
