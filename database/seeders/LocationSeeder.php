<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location = new Location;
        $location->name = 'algeria';
        $location->continent = 'africa';
        $location->save();

        $location = new Location;
        $location->name = 'tunis';
        $location->continent = 'africa';
        $location->save();

        $location = new Location;
        $location->name = 'england';
        $location->continent = 'europe';
        $location->save();

        $location = new Location;
        $location->name = 'italy';
        $location->continent = 'europe';
        $location->save();

        $location = new Location;
        $location->name = 'malaysia';
        $location->continent = 'asia';
        $location->save();

        $location = new Location;
        $location->name = 'japane';
        $location->continent = 'asia';
        $location->save();
    }
}
