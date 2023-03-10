<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookSaveRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\QueryBuilders\BookBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Book Management
 *
 * API Endpoints for managing books.
 */
class BooksController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * BooksController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Book::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the book resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[books] *string* - No-example
     * Comma-separated field/attribute names of the book resource to include in the response document.
     * The available fields for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam fields[author] *string* - No-example
     * Comma-separated field/attribute names of the author resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam fields[publisher] *string* - No-example
     * Comma-separated field/attribute names of the publisher resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `author`, `publisher`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`, `author.id`, `author.name`, `author.created_at`, `author.updated_at`, `publisher.id`, `publisher.name`, `publisher.created_at`, `publisher.updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\BookBuilder $query
     *
     * @return BookCollection
     */
    public function index(BookBuilder $query)
    {
        return new BookCollection($query->paginate(10));
    }

    /**
     * Create Resource.
     * Create a new book resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\BookSaveRequest $request
     * @param \App\Models\Book $book
     *
     * @return JsonResponse
     */
    public function store(BookSaveRequest $request, Book $book): JsonResponse
    {
        $book->fill($request->only($book->offsetGet('fillable')))
            ->save();

        $resource = (new BookResource($book))
            ->additional(['info' => 'The new book has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific book resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam book required *integer* - No-example
     * The identifier of a specific book resource.
     *
     * @queryParam fields[books] *string* - No-example
     * Comma-separated field/attribute names of the book resource to include in the response document.
     * The available fields for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam fields[author] *string* - No-example
     * Comma-separated field/attribute names of the author resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam fields[publisher] *string* - No-example
     * Comma-separated field/attribute names of the publisher resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `author`, `publisher`.
     *
     * @param \App\QueryBuilders\BookBuilder $query
     * @param \App\Models\Book $book
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return BookResource
     */
    public function show(BookBuilder $query, Book $book): BookResource
    {
        // return response()->json([
        //     "book" => $book
        // ], 200);
        return new BookResource($query->find($book->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific book resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam book required *integer* - No-example
     * The identifier of a specific book resource.
     *
     * @param \App\Http\Requests\BookSaveRequest $request
     * @param \App\Models\Book $book
     *
     * @return BookResource
     */
    public function update(BookSaveRequest $request, Book $book): BookResource
    {
        $book->fill($request->only($book->offsetGet('fillable')));

        if ($book->isDirty()) {
            $book->save();
        }

        return (new BookResource($book))
            ->additional(['info' => 'The book has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific book resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam book required *integer* - No-example
     * The identifier of a specific book resource.
     *
     * @param \App\Models\Book $book
     *
     * @throws \Exception
     *
     * @return BookResource
     */
    public function destroy(Book $book): BookResource
    {
        $book->delete();

        return (new BookResource($book))
            ->additional(['info' => 'The book has been deleted.']);
    }
}
