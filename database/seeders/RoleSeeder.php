<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'su',
        ]);

        DB::table('roles')->insert([
            'name' => 'admin',
        ]);

        DB::table('roles')->insert([
            'name' => 'user',
        ]);

    }
}
