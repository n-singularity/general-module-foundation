<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSubdivisionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(storage_path('data_seeder/location_subdivision.sql')));
    }
}
