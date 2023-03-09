<?php

namespace App\QueryBuilders;

use App\Http\Requests\BookGetRequest;
use App\Models\Book;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class BookBuilder extends Builder
{
// Used traits declaration
    /**
     * Current HTTP Request object.
     *
     * @var BookGetRequest
     */
    protected $request;

    /**
     * BookBuilder constructor.
     *
     * @param BookGetRequest $request
     */
    public function __construct(BookGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Book::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'books.id',
            'books.title',
            'books.description',
            'books.rating',
            'books.author_id',
            'books.publisher_id',
            'books.created_at',
            'books.updated_at',
            'author.id',
            'author.name',
            'author.created_at',
            'author.updated_at',
            'publisher.id',
            'publisher.name',
            'publisher.created_at',
            'publisher.updated_at',
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
            'title',
            'description',
            AllowedFilter::exact('rating'),
            AllowedFilter::exact('author_id'),
            AllowedFilter::exact('publisher_id'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('books.id'),
            'books.title',
            'books.description',
            AllowedFilter::exact('books.rating'),
            AllowedFilter::exact('books.author_id'),
            AllowedFilter::exact('books.publisher_id'),
            AllowedFilter::exact('books.created_at'),
            AllowedFilter::exact('books.updated_at'),
            AllowedFilter::exact('author.id'),
            'author.name',
            AllowedFilter::exact('author.created_at'),
            AllowedFilter::exact('author.updated_at'),
            AllowedFilter::exact('publisher.id'),
            'publisher.name',
            AllowedFilter::exact('publisher.created_at'),
            AllowedFilter::exact('publisher.updated_at'),
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
            'author',
            'publisher',
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
            'title',
            'description',
            'author.name',
            'publisher.name',
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
            'title',
            'description',
            'rating',
            'author_id',
            'publisher_id',
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
}
