<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->role_id = 1;
        $user->name = 'jervi';
        $user->email = 'su@gmail.com';
        $user->password = bcrypt('password');
        $user->phone_number = '0558054300';
        $user->location_id = 1;
        $user->save();

        $user = new User;
        $user->role_id = 2;
        $user->name = 'iverj';
        $user->email = 'admin@gmail.com';
        $user->password = bcrypt('password');
        $user->phone_number = '0558054300';
        $user->location_id = 2;
        $user->save();

        $user = new User;
        $user->role_id = 3;
        $user->name = 'bruh';
        $user->email = 'sss@gmail.com';
        $user->password = bcrypt('password');
        $user->phone_number = '0558054300';
        $user->location_id = 3;
        $user->save();
    }
}
