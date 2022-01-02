<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        $location = new RoleSeeder;
        $user = new RoleSeeder;
        $post = new RoleSeeder;
        $tag = new RoleSeeder;

        $role->run();
        $location->run();
        $user->run();
        $post->run();
        $tag->run();
    }
}
