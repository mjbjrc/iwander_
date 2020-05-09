<?php

use Illuminate\Database\Seeder;

class ItinerariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $path = 'database/migrations/itineraries.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('Itineraries table seeded!');
    }
}
