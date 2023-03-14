<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id')->toArray();
        $bookIds = Book::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            foreach ($bookIds as $bookId) {
                Rental::factory(5)->create([
                    'user_id' => $userId,
                    'book_id' => $bookId,
                ]);
            }
        }
    }
}
