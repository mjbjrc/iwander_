<?php

use Illuminate\Database\Seeder;

class EventsItinerariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $path = 'database/migrations/events_itineraries.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('Events_itineraries table seeded!');
    }
}
