<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $path = 'database/migrations/events.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('Events table seeded!');
    }
}
