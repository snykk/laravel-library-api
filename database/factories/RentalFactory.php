<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RentalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rental::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'rental_date' => Carbon::now(),
            'rental_duration' => 7,
            'return_date' => Carbon::now()->addDays(rand(1, 6)),
            'status' => RENTAL::STATUS_RETURNED,
            'is_due' => 0,

        ];
    }
}
