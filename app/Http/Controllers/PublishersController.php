<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublisherSaveRequest;
use App\Http\Resources\PublisherCollection;
use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use App\QueryBuilders\PublisherBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Publisher Management
 *
 * API Endpoints for managing publishers.
 */
class PublishersController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * PublishersController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Publisher::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the publisher resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[publishers] *string* - No-example
     * Comma-separated field/attribute names of the publisher resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `books`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\PublisherBuilder $query
     *
     * @return PublisherCollection
     */
    public function index(PublisherBuilder $query): PublisherCollection
    {
        return new PublisherCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new publisher resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\PublisherSaveRequest $request
     * @param \App\Models\Publisher $publisher
     *
     * @return JsonResponse
     */
    public function store(PublisherSaveRequest $request, Publisher $publisher): JsonResponse
    {
        $publisher->fill($request->only($publisher->offsetGet('fillable')))
            ->save();

        $resource = (new PublisherResource($publisher))
            ->additional(['info' => 'The new publisher has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific publisher resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam publisher required *integer* - No-example
     * The identifier of a specific publisher resource.
     *
     * @queryParam fields[publishers] *string* - No-example
     * Comma-separated field/attribute names of the publisher resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `books`.
     *
     * @param \App\QueryBuilders\PublisherBuilder $query
     * @param \App\Models\Publisher $publisher
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return PublisherResource
     */
    public function show(PublisherBuilder $query, Publisher $publisher): PublisherResource
    {
        return new PublisherResource($query->find($publisher->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific publisher resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam publisher required *integer* - No-example
     * The identifier of a specific publisher resource.
     *
     * @param \App\Http\Requests\PublisherSaveRequest $request
     * @param \App\Models\Publisher $publisher
     *
     * @return PublisherResource
     */
    public function update(PublisherSaveRequest $request, Publisher $publisher): PublisherResource
    {
        $publisher->fill($request->only($publisher->offsetGet('fillable')));

        if ($publisher->isDirty()) {
            $publisher->save();
        }

        return (new PublisherResource($publisher))
            ->additional(['info' => 'The publisher has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific publisher resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam publisher required *integer* - No-example
     * The identifier of a specific publisher resource.
     *
     * @param \App\Models\Publisher $publisher
     *
     * @throws \Exception
     *
     * @return PublisherResource
     */
    public function destroy(Publisher $publisher): PublisherResource
    {
        $publisher->delete();

        return (new PublisherResource($publisher))
            ->additional(['info' => 'The publisher has been deleted.']);
    }
}
