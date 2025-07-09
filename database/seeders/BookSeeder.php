<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Book::factory()->count(10)->create([
            'total_copies' => rand(1, 5),
        ]);
    }
}
