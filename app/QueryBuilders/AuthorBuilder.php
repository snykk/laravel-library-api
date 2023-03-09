<?php

namespace App\QueryBuilders;

use App\Http\Requests\AuthorGetRequest;
use App\Models\Author;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class AuthorBuilder extends Builder
{
    // Used traits declaration
    /**
     * Current HTTP Request object.
     *
     * @var AuthorGetRequest
     */
    protected $request;

    /**
     * AuthorBuilder constructor.
     *
     * @param AuthorGetRequest $request
     */
    public function __construct(AuthorGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Author::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'authors.id',
            'authors.name',
            'authors.created_at',
            'authors.updated_at',
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
            'name',
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('authors.id'),
            'authors.name',
            AllowedFilter::exact('authors.created_at'),
            AllowedFilter::exact('authors.updated_at'),
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
            'books',
        ];
    }

    protected function getAllowedAppended(): array
    {
        return [
            'list_book',
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
            'name',
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
            'name',
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

    // public function query(): QueryBuilder
    // {
    //     dd(parent::query()->toSql());
    // }
}
