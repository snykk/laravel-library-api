<?php

namespace App\QueryBuilders;

use App\Http\Requests\SettingGetRequest;
use App\Models\Setting;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SettingBuilder extends Builder
{
    /**
     * SettingBuilder constructor.
     *
     * @param SettingGetRequest $request
     */
    public function __construct(SettingGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Setting::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'settings.id',
            'settings.type',
            'settings.key',
            'settings.value',
            'settings.deleted_at',
            'settings.created_at',
            'settings.updated_at',
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
            'type',
            'key',
            'value',
            AllowedFilter::exact('deleted_at'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('settings.id'),
            'settings.type',
            'settings.key',
            'settings.value',
            AllowedFilter::exact('settings.deleted_at'),
            AllowedFilter::exact('settings.created_at'),
            AllowedFilter::exact('settings.updated_at'),
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
            'key',
            'value',
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
            'type',
            'key',
            'value',
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
