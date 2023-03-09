<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingSaveRequest;
use App\Http\Resources\SettingCollection;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\QueryBuilders\SettingBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Setting Management
 *
 * API Endpoints for managing settings.
 */
class SettingsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Setting::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the setting resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[settings] *string* - No-example
     * Comma-separated field/attribute names of the setting resource to include in the response document.
     * The available fields for current endpoint are: `id`, `type`, `key`, `value`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `type`, `key`, `value`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `type`, `key`, `value`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\SettingBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return SettingCollection
     */
    public function index(SettingBuilder $query): SettingCollection
    {
        return new SettingCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new setting resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\SettingSaveRequest $request
     * @param \App\Models\Setting                   $setting
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse
     */
    public function store(SettingSaveRequest $request, Setting $setting): JsonResponse
    {
        $setting->fill($request->only($setting->offsetGet('fillable')))
            ->save();

        $resource = (new SettingResource($setting))
            ->additional(['info' => 'The new setting has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific setting resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam setting required *integer* - No-example
     * The identifier of a specific setting resource.
     *
     * @queryParam fields[settings] *string* - No-example
     * Comma-separated field/attribute names of the setting resource to include in the response document.
     * The available fields for current endpoint are: `id`, `type`, `key`, `value`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\SettingBuilder $query
     * @param \App\Models\Setting               $setting
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return SettingResource
     */
    public function show(SettingBuilder $query, Setting $setting): SettingResource
    {
        return new SettingResource($query->find($setting->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific setting resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam setting required *integer* - No-example
     * The identifier of a specific setting resource.
     *
     * @param \App\Http\Requests\SettingSaveRequest $request
     * @param \App\Models\Setting                   $setting
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return SettingResource
     */
    public function update(SettingSaveRequest $request, Setting $setting): SettingResource
    {
        $setting->fill($request->only($setting->offsetGet('fillable')));

        if ($setting->isDirty()) {
            $setting->save();
        }

        return (new SettingResource($setting))
            ->additional(['info' => 'The setting has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific setting resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam setting required *integer* - No-example
     * The identifier of a specific setting resource.
     *
     * @param \App\Models\Setting $setting
     *
     * @throws \Exception
     *
     * @return SettingResource
     */
    public function destroy(Setting $setting): SettingResource
    {
        $setting->delete();

        return (new SettingResource($setting))
            ->additional(['info' => 'The setting has been deleted.']);
    }
}
