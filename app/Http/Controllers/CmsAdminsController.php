<?php

namespace App\Http\Controllers;

use App\Http\Requests\CmsAdminSaveRequest;
use App\Http\Resources\CmsAdminCollection;
use App\Http\Resources\CmsAdminResource;
use App\Models\CmsAdmin;
use App\QueryBuilders\CmsAdminBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * @group Cms Admin Management
 *
 * API Endpoints for managing cms admins.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CmsAdminsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * CmsAdminsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(CmsAdmin::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the cms admin resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[cms_admins] *string* - No-example
     * Comma-separated field/attribute names of the cms_admin resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `medium_profile_picture`, `small_profile_picture`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\CmsAdminBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return CmsAdminCollection
     */
    public function index(CmsAdminBuilder $query): CmsAdminCollection
    {
        return new CmsAdminCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new cms admin resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\CmsAdminSaveRequest $request
     * @param \App\Models\CmsAdmin                   $cmsAdmin
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return JsonResponse
     */
    public function store(CmsAdminSaveRequest $request, CmsAdmin $cmsAdmin): JsonResponse
    {
        $cmsAdmin->fill($request->only($cmsAdmin->offsetGet('fillable')));
        $cmsAdmin->password = Hash::make($cmsAdmin->password);
        $cmsAdmin->save();

        $roles = is_array($request->input('role_names')) ?
            $request->input('role_names') :
            explode(',', $request->input('role_names'));

        $cmsAdmin->saveProfilePicture($request)
            ->syncRoles($roles);

        $resource = (new CmsAdminResource($cmsAdmin))
            ->additional(['info' => 'The new cms admin has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific cms admin resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam cmsAdmin required *integer* - No-example
     * The identifier of a specific cms admin resource.
     *
     * @queryParam fields[cms_admins] *string* - No-example
     * Comma-separated field/attribute names of the cms_admin resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `medium_profile_picture`, `small_profile_picture`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\CmsAdminBuilder $query
     * @param \App\Models\CmsAdmin               $cmsAdmin
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return CmsAdminResource
     */
    public function show(CmsAdminBuilder $query, CmsAdmin $cmsAdmin): CmsAdminResource
    {
        return new CmsAdminResource($query->find($cmsAdmin->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific cms admin resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam cmsAdmin required *integer* - No-example
     * The identifier of a specific cms admin resource.
     *
     * @param \App\Http\Requests\CmsAdminSaveRequest $request
     * @param \App\Models\CmsAdmin                   $cmsAdmin
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return CmsAdminResource
     */
    public function update(CmsAdminSaveRequest $request, CmsAdmin $cmsAdmin): CmsAdminResource
    {
        $cmsAdmin->fill($request->only($cmsAdmin->offsetGet('fillable')));

        if ($cmsAdmin->isDirty()) {
            if ($cmsAdmin->isDirty('password')) {
                $cmsAdmin->password = Hash::make($cmsAdmin->password);
            }
            $cmsAdmin->save();
        }

        $roles = is_array($request->input('role_names')) ?
            $request->input('role_names') :
            explode(',', $request->input('role_names'));

        $cmsAdmin->saveProfilePicture($request)
            ->syncRoles($roles);

        return (new CmsAdminResource($cmsAdmin))
            ->additional(['info' => 'The cms admin has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific cms admin resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam cmsAdmin required *integer* - No-example
     * The identifier of a specific cms admin resource.
     *
     * @param \App\Models\CmsAdmin $cmsAdmin
     *
     * @throws \Exception
     *
     * @return CmsAdminResource
     */
    public function destroy(CmsAdmin $cmsAdmin): CmsAdminResource
    {
        $cmsAdmin->delete();

        return (new CmsAdminResource($cmsAdmin))
            ->additional(['info' => 'The cms admin has been deleted.']);
    }
}
