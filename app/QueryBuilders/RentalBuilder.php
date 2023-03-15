<?php

namespace App\QueryBuilders;

use App\Http\Requests\RentalGetRequest;
use App\Models\Rental;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class RentalBuilder extends Builder
{
    // Used traits declaration
    /**
     * Current HTTP Request object.
     *
     * @var RentalGetRequest
     */
    protected $request;

    /**
     * RentalBuilder constructor.
     *
     * @param RentalGetRequest $request
     */
    public function __construct(RentalGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Rental::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'rentals.id',
            'rentals.user_id',
            'rentals.book_id',
            'rentals.rental_date',
            'rentals.rental_duration',
            'rentals.return_date',
            'rentals.status',
            'rentals.is_due',
            'rentals.created_at',
            'rentals.updated_at',
            'user.id',
            'user.name',
            'user.email',
            'user.email_verified_at',
            'user.password',
            'user.reviews',
            'user.remember_token',
            'user.created_at',
            'user.updated_at',
            'book.id',
            'book.title',
            'book.description',
            'book.rating',
            'book.author_id',
            'book.publisher_id',
            'book.created_at',
            'book.updated_at',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any filter operations.
     *
     * @return array
     */
    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('book_id'),
            AllowedFilter::exact('rental_date'),
            AllowedFilter::exact('rental_duration'),
            AllowedFilter::exact('return_date'),
            'status',
            AllowedFilter::exact('is_due'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('rentals.id'),
            AllowedFilter::exact('rentals.user_id'),
            AllowedFilter::exact('rentals.book_id'),
            AllowedFilter::exact('rentals.rental_date'),
            AllowedFilter::exact('rentals.rental_duration'),
            AllowedFilter::exact('rentals.return_date'),
            'rentals.status',
            AllowedFilter::exact('rentals.is_due'),
            AllowedFilter::exact('rentals.created_at'),
            AllowedFilter::exact('rentals.updated_at'),
            AllowedFilter::exact('user.id'),
            'user.name',
            'user.email',
            AllowedFilter::exact('user.email_verified_at'),
            'user.password',
            AllowedFilter::exact('user.reviews'),
            'user.remember_token',
            AllowedFilter::exact('user.created_at'),
            AllowedFilter::exact('user.updated_at'),
            AllowedFilter::exact('book.id'),
            'book.title',
            'book.description',
            AllowedFilter::exact('book.rating'),
            AllowedFilter::exact('book.author_id'),
            AllowedFilter::exact('book.publisher_id'),
            AllowedFilter::exact('book.created_at'),
            AllowedFilter::exact('book.updated_at'),
        ];
    }

    /**
     * Get a list of allowed relationships that can be used in any include operations.
     *
     * @return string[]
     */
    protected function getAllowedIncludes(): array
    {
        return [
            'user',
            'book',
        ];
    }

    /**
     * Get a list of allowed searchable columns which can be used in any search operations.
     *
     * @return string[]
     */
    protected function getAllowedSearch(): array
    {
        return [
            'status',
            'user.name',
            'user.email',
            'user.password',
            'user.remember_token',
            'book.title',
            'book.description',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any sort operations.
     *
     * @return string[]
     */
    protected function getAllowedSorts(): array
    {
        return [
            'id',
            'user_id',
            'book_id',
            'rental_date',
            'rental_duration',
            'return_date',
            'status',
            'is_due',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Get the default sort column that will be used in any sort operation.
     *
     * @return string
     */
    protected function getDefaultSort(): string
    {
        return 'id';
    }

    public function getUserRental($user_id, $length = 30)
    {
        return $this->query()->where('user_id', '=', $user_id)->jsonPaginate($length);
    }
}
