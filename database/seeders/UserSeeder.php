<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => 1,
            'name' => Str::random(10),
            'email' => 'su@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '0558054300',
            'uuid' => Helper::random(),
        ]);

        DB::table('users')->insert([
            'role_id' => 2,
            'name' => Str::random(10),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '0558054300',
            'uuid' => Helper::random(),
        ]);

        DB::table('users')->insert([
            'role_id' => 3,
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '0558054300',
            'uuid' => Helper::random(),
        ]);


        DB::table('users')->insert([
            'role_id' => 3,
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '0558054300',
            'uuid' => Helper::random(),
        ]);
    }
}
