<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = new Tag;
        $tag->name = 'football';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'tennis';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'news';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'algeria';
        $tag->save();

        $tag = new Tag;
        $tag->name = 'bruh';
        $tag->save();
    }
}
