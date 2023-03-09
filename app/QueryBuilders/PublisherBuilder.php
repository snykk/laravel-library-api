<?php

namespace App\QueryBuilders;

use App\Http\Requests\PublisherGetRequest;
use App\Models\Publisher;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class PublisherBuilder extends Builder
{
// Used traits declaration
    /**
     * Current HTTP Request object.
     *
     * @var PublisherGetRequest
     */
    protected $request;

    /**
     * PublisherBuilder constructor.
     *
     * @param PublisherGetRequest $request
     */
    public function __construct(PublisherGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Publisher::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'publishers.id',
            'publishers.name',
            'publishers.created_at',
            'publishers.updated_at',
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
            AllowedFilter::exact('publishers.id'),
            'publishers.name',
            AllowedFilter::exact('publishers.created_at'),
            AllowedFilter::exact('publishers.updated_at'),
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
}
