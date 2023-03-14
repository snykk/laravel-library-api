<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalSaveRequest;
use App\Http\Resources\RentalCollection;
use App\Http\Resources\RentalResource;
use App\Models\Rental;
use App\QueryBuilders\RentalBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Rental Management
 *
 * API Endpoints for managing rentals.
 */
class RentalsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * RentalsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Rental::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the rental resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[rentals] *string* - No-example
     * Comma-separated field/attribute names of the rental resource to include in the response document.
     * The available fields for current endpoint are: `id`, `user_id`, `book_id`, `rental_date`, `rental_duration`, `return_date`, `status`, `is_due`, `created_at`, `updated_at`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam fields[book] *string* - No-example
     * Comma-separated field/attribute names of the book resource to include in the response document.
     * The available fields for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `user`, `book`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `user_id`, `book_id`, `rental_date`, `rental_duration`, `return_date`, `status`, `is_due`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `user_id`, `book_id`, `rental_date`, `rental_duration`, `return_date`, `status`, `is_due`, `created_at`, `updated_at`, `user.id`, `user.name`, `user.email`, `user.email_verified_at`, `user.password`, `user.reviews`, `user.remember_token`, `user.created_at`, `user.updated_at`, `book.id`, `book.title`, `book.description`, `book.rating`, `book.author_id`, `book.publisher_id`, `book.created_at`, `book.updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\RentalBuilder $query
     *
     * @return RentalCollection
     */
    public function index(RentalBuilder $query): RentalCollection
    {
        return new RentalCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new rental resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\RentalSaveRequest $request
     * @param \App\Models\Rental $rental
     *
     * @return JsonResponse
     */
    public function store(RentalSaveRequest $request, Rental $rental): JsonResponse
    {
        $rental->fill($request->only($rental->offsetGet('fillable')))
            ->save();

        $resource = (new RentalResource($rental))
            ->additional(['info' => 'The new rental has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific rental resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam rental required *integer* - No-example
     * The identifier of a specific rental resource.
     *
     * @queryParam fields[rentals] *string* - No-example
     * Comma-separated field/attribute names of the rental resource to include in the response document.
     * The available fields for current endpoint are: `id`, `user_id`, `book_id`, `rental_date`, `rental_duration`, `return_date`, `status`, `is_due`, `created_at`, `updated_at`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `reviews`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam fields[book] *string* - No-example
     * Comma-separated field/attribute names of the book resource to include in the response document.
     * The available fields for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `user`, `book`.
     *
     * @param \App\QueryBuilders\RentalBuilder $query
     * @param \App\Models\Rental $rental
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return RentalResource
     */
    public function show(RentalBuilder $query, Rental $rental): RentalResource
    {
        return new RentalResource($query->find($rental->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific rental resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam rental required *integer* - No-example
     * The identifier of a specific rental resource.
     *
     * @param \App\Http\Requests\RentalSaveRequest $request
     * @param \App\Models\Rental $rental
     *
     * @return RentalResource
     */
    public function update(RentalSaveRequest $request, Rental $rental): RentalResource
    {
        $rental->fill($request->only($rental->offsetGet('fillable')));

        if ($rental->isDirty()) {
            $rental->save();
        }

        return (new RentalResource($rental))
            ->additional(['info' => 'The rental has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific rental resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam rental required *integer* - No-example
     * The identifier of a specific rental resource.
     *
     * @param \App\Models\Rental $rental
     *
     * @throws \Exception
     *
     * @return RentalResource
     */
    public function destroy(Rental $rental): RentalResource
    {
        $rental->delete();

        return (new RentalResource($rental))
            ->additional(['info' => 'The rental has been deleted.']);
    }
}
