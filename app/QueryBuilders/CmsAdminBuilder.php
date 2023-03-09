<?php

namespace App\QueryBuilders;

use App\Http\Requests\CmsAdminGetRequest;
use App\Models\CmsAdmin;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CmsAdminBuilder extends Builder
{
    /**
     * The Query Builder instance for the current resource list generator.
     *
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * CmsAdminBuilder constructor.
     *
     * @param CmsAdminGetRequest $request
     */
    public function __construct(CmsAdminGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(CmsAdmin::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'cms_admins.id',
            'cms_admins.name',
            'cms_admins.email',
            'cms_admins.password',
            'cms_admins.remember_token',
            'cms_admins.deleted_at',
            'cms_admins.created_at',
            'cms_admins.updated_at',
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
            'email',
            'password',
            'remember_token',
            AllowedFilter::exact('deleted_at'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('cms_admins.id'),
            'cms_admins.name',
            'cms_admins.email',
            'cms_admins.password',
            'cms_admins.remember_token',
            AllowedFilter::exact('cms_admins.deleted_at'),
            AllowedFilter::exact('cms_admins.created_at'),
            AllowedFilter::exact('cms_admins.updated_at'),
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
            'roles',
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
            'email',
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
            'email',
            'password',
            'remember_token',
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

    /**
     * Get default query builder.
     *
     * @return QueryBuilder
     */
    public function query(): QueryBuilder
    {
        return parent::query()
            ->allowedAppends(['medium_profile_picture', 'small_profile_picture', 'role_names']);
    }
}
