<?php

namespace App\QueryBuilders;

use App\Http\Requests\SeoMetaGetRequest;
use App\Models\SeoMeta;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SeoMetaBuilder extends Builder
{
    /**
     * SeoMetaBuilder constructor.
     *
     * @param SeoMetaGetRequest $request
     */
    public function __construct(SeoMetaGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(SeoMeta::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'seo_metas.id',
            'seo_metas.seo_url',
            'seo_metas.model',
            'seo_metas.foreign_key',
            'seo_metas.locale',
            'seo_metas.seo_title',
            'seo_metas.seo_description',
            'seo_metas.open_graph_type',
            'seo_metas.deleted_at',
            'seo_metas.created_at',
            'seo_metas.updated_at',
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
            'seo_url',
            'model',
            AllowedFilter::exact('foreign_key'),
            'locale',
            'seo_title',
            'seo_description',
            'open_graph_type',
            AllowedFilter::exact('deleted_at'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::scope('url_based_only'),
            AllowedFilter::exact('seo_metas.id'),
            'seo_metas.seo_url',
            'seo_metas.model',
            AllowedFilter::exact('seo_metas.foreign_key'),
            'seo_metas.locale',
            'seo_metas.seo_title',
            'seo_metas.seo_description',
            'seo_metas.open_graph_type',
            AllowedFilter::exact('seo_metas.deleted_at'),
            AllowedFilter::exact('seo_metas.created_at'),
            AllowedFilter::exact('seo_metas.updated_at'),
            AllowedFilter::scope('seo_metas.url_based_only'),
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
            'seo_url',
            'seo_title',
            'seo_description',
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
            'seo_url',
            'model',
            'foreign_key',
            'locale',
            'seo_title',
            'seo_description',
            'open_graph_type',
            'deleted_at',
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
