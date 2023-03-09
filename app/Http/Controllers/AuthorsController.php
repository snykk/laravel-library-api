<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorSaveRequest;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\QueryBuilders\AuthorBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Author Management
 *
 * API Endpoints for managing authors.
 */
class AuthorsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * AuthorsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Author::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the author resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[authors] *string* - No-example
     * Comma-separated field/attribute names of the author resource to include in the response document.
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
     * @param \App\QueryBuilders\AuthorBuilder $query
     *
     * @return AuthorCollection
     */
    public function index(AuthorBuilder $query): AuthorCollection
    {
        return new AuthorCollection($query->paginate(10));
    }

    /**
     * Create Resource.
     * Create a new author resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\AuthorSaveRequest $request
     * @param \App\Models\Author $author
     *
     * @return JsonResponse
     */
    public function store(AuthorSaveRequest $request, Author $author): JsonResponse
    {
        $author->fill($request->only($author->offsetGet('fillable')))
            ->save();

        $resource = (new AuthorResource($author))
            ->additional(['info' => 'The new author has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific author resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam author required *integer* - No-example
     * The identifier of a specific author resource.
     *
     * @queryParam fields[authors] *string* - No-example
     * Comma-separated field/attribute names of the author resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `books`.
     *
     * @param \App\QueryBuilders\AuthorBuilder $query
     * @param \App\Models\Author $author
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return AuthorResource
     */
    public function show(AuthorBuilder $query, Author $author): AuthorResource
    {
        return new AuthorResource($query->find($author->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific author resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam author required *integer* - No-example
     * The identifier of a specific author resource.
     *
     * @param \App\Http\Requests\AuthorSaveRequest $request
     * @param \App\Models\Author $author
     *
     * @return AuthorResource
     */
    public function update(AuthorSaveRequest $request, Author $author): AuthorResource
    {
        $author->fill($request->only($author->offsetGet('fillable')));

        if ($author->isDirty()) {
            $author->save();
        }

        return (new AuthorResource($author))
            ->additional(['info' => 'The author has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific author resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam author required *integer* - No-example
     * The identifier of a specific author resource.
     *
     * @param \App\Models\Author $author
     *
     * @throws \Exception
     *
     * @return AuthorResource
     */
    public function destroy(Author $author): AuthorResource
    {
        $author->delete();

        return (new AuthorResource($author))
            ->additional(['info' => 'The author has been deleted.']);
    }
}
