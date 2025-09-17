<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run() {
        \App\Models\Author::factory(5)->create();
        \App\Models\Genre::factory(5)->create();
        \App\Models\User::factory(10)->create();
        \App\Models\Book::factory(20)->create();
    }
}
