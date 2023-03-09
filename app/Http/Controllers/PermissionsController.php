<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionSaveRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\QueryBuilders\PermissionBuilder;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;

/**
 * @group Permission Management
 *
 * API Endpoints for managing permissions.
 */
class PermissionsController extends Controller
{
    /**
     * Resource Collection.
     * Display a collection of the permission resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[permissions] *string* - No-example
     * Comma-separated field/attribute names of the permission resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `roles`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\PermissionBuilder $query
     *
     * @return PermissionCollection
     */
    public function index(PermissionBuilder $query): PermissionCollection
    {
        return new PermissionCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new permission resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\PermissionSaveRequest $request
     * @param \Spatie\Permission\Models\Permission     $permission
     *
     * @return JsonResponse
     */
    public function store(PermissionSaveRequest $request, Permission $permission): JsonResponse
    {
        $permission->fill($request->only(['name', 'guard_name']))
            ->save();

        $resource = (new PermissionResource($permission))
            ->additional(['info' => 'The new permission has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific permission resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam permission required *integer* - No-example
     * The identifier of a specific permission resource.
     *
     * @queryParam fields[permissions] *string* - No-example
     * Comma-separated field/attribute names of the permission resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `roles`.
     *
     * @param \App\QueryBuilders\PermissionBuilder $query
     * @param \Spatie\Permission\Models\Permission $permission
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return PermissionResource
     */
    public function show(PermissionBuilder $query, Permission $permission): PermissionResource
    {
        return new PermissionResource($query->find($permission->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific permission resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam permission required *integer* - No-example
     * The identifier of a specific permission resource.
     *
     * @param \App\Http\Requests\PermissionSaveRequest $request
     * @param \Spatie\Permission\Models\Permission     $permission
     *
     * @return PermissionResource
     */
    public function update(PermissionSaveRequest $request, Permission $permission): PermissionResource
    {
        $permission->fill($request->only(['name', 'guard_name']));

        if ($permission->isDirty()) {
            $permission->save();
        }

        return (new PermissionResource($permission))
            ->additional(['info' => 'The permission has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific permission resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam permission required *integer* - No-example
     * The identifier of a specific permission resource.
     *
     * @param \Spatie\Permission\Models\Permission $permission
     *
     * @throws \Exception
     *
     * @return PermissionResource
     */
    public function destroy(Permission $permission): PermissionResource
    {
        $permission->delete();

        return (new PermissionResource($permission))
            ->additional(['info' => 'The permission has been deleted.']);
    }
}
