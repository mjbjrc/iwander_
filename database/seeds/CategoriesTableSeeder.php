<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $path = 'database/migrations/categories.sql';
      DB::unprepared(file_get_contents($path));
      $this->command->info('categories table seeded!');
    }
}
