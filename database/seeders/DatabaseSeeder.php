<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\TagSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BadgeSeeder;
use Database\Seeders\LocationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $role = new RoleSeeder;
        $location = new LocationSeeder;
        $user = new UserSeeder;
        //$post = new PostSeeder;
        $tags = new TagSeeder;
        $badges = new BadgeSeeder;

        $role->run();
        $location->run();
        $user->run();
        //$post->run();
        $tags->run();
        $badges->run();
    }
}
