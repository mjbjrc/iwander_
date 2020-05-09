<?php

use Illuminate\Database\Seeder;
use App\Keyword;

class KeywordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $path = 'database/migrations/keywords.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('keywords table seeded!');
    }
}
