<?php

namespace App\QueryBuilders;

use App\Http\Requests\ReviewGetRequest;
use App\Models\Review;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ReviewBuilder extends Builder
{
    // Used traits declaration
    /**
     * Current HTTP Request object.
     *
     * @var ReviewGetRequest
     */
    protected $request;

    /**
     * ReviewBuilder constructor.
     *
     * @param ReviewGetRequest $request
     */
    public function __construct(ReviewGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Review::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'reviews.id',
            'reviews.comment',
            'reviews.rating',
            'reviews.user_id',
            'reviews.book_id',
            'reviews.created_at',
            'reviews.updated_at',
            'user.id',
            'user.name',
            'user.email',
            'user.email_verified_at',
            'user.password',
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
            'comment',
            AllowedFilter::exact('rating'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('book_id'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('reviews.id'),
            'reviews.comment',
            AllowedFilter::exact('reviews.rating'),
            AllowedFilter::exact('reviews.user_id'),
            AllowedFilter::exact('reviews.book_id'),
            AllowedFilter::exact('reviews.created_at'),
            AllowedFilter::exact('reviews.updated_at'),
            AllowedFilter::exact('user.id'),
            'user.name',
            'user.email',
            AllowedFilter::exact('user.email_verified_at'),
            'user.password',
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
            'comment',
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
            'comment',
            'rating',
            'user_id',
            'book_id',
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

    public function getUserReview($user_id, $length = 30)
    {
        return $this->query()->where('user_id', '=', $user_id)->jsonPaginate($length);
    }
}
