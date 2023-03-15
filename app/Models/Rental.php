<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
// Interface implementation
{
    use HasFactory;

    const STATUS_RETURNED = "returned";

    const STATUS_BORROWED = "borrowed";

    const STATUS_ENUM = [
        self::STATUS_RETURNED,
        self::STATUS_BORROWED,
    ];


    // Used traits declaration
    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'rental_date',
        'return_date',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'rental_date',
        'rental_duration',
        'return_date',
        'status',
        'is_due',
    ];
    // Model accessors declaration

    /**
     * Model relationship definition.
     * Rental belongs to Book
     *
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Model relationship definition.
     * Rental belongs to User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
