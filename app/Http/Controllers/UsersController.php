<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSaveRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\QueryBuilders\UserBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group User Management
 *
 * API Endpoints for managing users.
 */
class UsersController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(User::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the user resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[users] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `rentals`, `reviews`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\UserBuilder $query
     *
     * @return UserCollection
     */
    public function index(UserBuilder $query): UserCollection
    {
        return new UserCollection($query->paginate(10));
    }

    /**
     * Create Resource.
     * Create a new user resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\UserSaveRequest $request
     * @param \App\Models\User $user
     *
     * @return JsonResponse
     */
    public function store(UserSaveRequest $request, User $user): JsonResponse
    {
        $user->fill($request->only($user->offsetGet('fillable')))
            ->save();

        $resource = (new UserResource($user))
            ->additional(['info' => 'The new user has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific user resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam user required *integer* - No-example
     * The identifier of a specific user resource.
     *
     * @queryParam fields[users] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `rentals`, `reviews`.
     *
     * @param \App\QueryBuilders\UserBuilder $query
     * @param \App\Models\User $user
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return UserResource
     */
    public function show(UserBuilder $query, User $user): UserResource
    {
        return new UserResource($query->find($user->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific user resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam user required *integer* - No-example
     * The identifier of a specific user resource.
     *
     * @param \App\Http\Requests\UserSaveRequest $request
     * @param \App\Models\User $user
     *
     * @return UserResource
     */
    public function update(UserSaveRequest $request, User $user): UserResource
    {
        $user->fill($request->only($user->offsetGet('fillable')));

        if ($user->isDirty()) {
            $user->save();
        }

        return (new UserResource($user))
            ->additional(['info' => 'The user has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific user resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam user required *integer* - No-example
     * The identifier of a specific user resource.
     *
     * @param \App\Models\User $user
     *
     * @throws \Exception
     *
     * @return UserResource
     */
    public function destroy(User $user): UserResource
    {
        $user->delete();

        return (new UserResource($user))
            ->additional(['info' => 'The user has been deleted.']);
    }
}
