<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authorIds = Author::pluck('id')->toArray();
        $publisherIds = Publisher::pluck('id')->toArray();

        foreach ($authorIds as $authorId) {
            foreach ($publisherIds as $publisherId) {
                Book::factory(5)->create([
                    'author_id' => $authorId,
                    'publisher_id' => $publisherId,
                ]);
            }
        }
    }
}
