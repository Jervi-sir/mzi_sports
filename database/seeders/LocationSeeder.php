<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'algeria',
            'continent' => 'africa',
        ]);

        DB::table('locations')->insert([
            'name' => 'tunis',
            'continent' => 'africa',
        ]);

        DB::table('locations')->insert([
            'name' => 'england',
            'continent' => 'europe',
        ]);

        DB::table('locations')->insert([
            'name' => 'italy',
            'continent' => 'europe',
        ]);

        DB::table('locations')->insert([
            'name' => 'malaysia',
            'continent' => 'asia',
        ]);


        DB::table('locations')->insert([
            'name' => 'japane',
            'continent' => 'asia',
        ]);

    }
}
