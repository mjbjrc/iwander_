<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\District;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $path = 'database/migrations/districts.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('District table seeded!');
    }
}
