<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewSaveRequest;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use App\QueryBuilders\ReviewBuilder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * @group Review Management
 *
 * API Endpoints for managing reviews.
 */
class ReviewsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * ReviewsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Review::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the review resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[reviews] *string* - No-example
     * Comma-separated field/attribute names of the review resource to include in the response document.
     * The available fields for current endpoint are: `id`, `comment`, `rating`, `user_id`, `book_id`, `created_at`, `updated_at`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`.
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
     * The available fields for sorting operation in current endpoint are: `id`, `comment`, `rating`, `user_id`, `book_id`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `comment`, `rating`, `user_id`, `book_id`, `created_at`, `updated_at`, `user.id`, `user.name`, `user.email`, `user.email_verified_at`, `user.password`, `user.remember_token`, `user.created_at`, `user.updated_at`, `book.id`, `book.title`, `book.description`, `book.rating`, `book.author_id`, `book.publisher_id`, `book.created_at`, `book.updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\ReviewBuilder $query
     *
     * @return ReviewCollection
     */
    public function index(ReviewBuilder $query): ReviewCollection
    {

        return new ReviewCollection($query->paginate(length: 10));
    }


    public function userReview(ReviewBuilder $query): ReviewCollection
    {
        return new ReviewCollection($query->getUserReview((int) auth()->user()->id, length: 10));
    }

    /**
     * Create Resource.
     * Create a new review resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\ReviewSaveRequest $request
     * @param \App\Models\Review $review
     *
     * @return JsonResponse
     */
    public function store(ReviewSaveRequest $request, Review $review): JsonResponse
    {
        // start a transaction
        DB::beginTransaction();

        try {
            $review->fill($request->only($review->offsetGet('fillable')));

            $review->user_id = auth()->user()->id;
            $userReview = Review::where('user_id', $review->user_id)
                ->where('book_id', $review->book_id)
                ->first();

            if ($userReview) {
                return response()->json([
                    "message" => "user already make a review on this book",
                ], 400);
            }

            $review->save();

            // get rating of certain book
            $rating = Review::where('book_id', $review->book_id)
                ->avg('rating');

            // update book rating
            $book = Book::where('id', $review->book_id)->first();
            $book->rating = $rating;
            $book->save();

            // update user rating
            $user = User::where('id', $review->user_id)->first();
            $user->reviews += 1;
            $user->save();

            DB::commit();

            $resource = (new ReviewResource($review))
                ->additional(['info' => 'The new review has been saved.']);

            return $resource->toResponse($request)->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => "an error occure when try to create a reviews",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Resource.
     * Display a specific review resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam review required *integer* - No-example
     * The identifier of a specific review resource.
     *
     * @queryParam fields[reviews] *string* - No-example
     * Comma-separated field/attribute names of the review resource to include in the response document.
     * The available fields for current endpoint are: `id`, `comment`, `rating`, `user_id`, `book_id`, `created_at`, `updated_at`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam fields[book] *string* - No-example
     * Comma-separated field/attribute names of the book resource to include in the response document.
     * The available fields for current endpoint are: `id`, `title`, `description`, `rating`, `author_id`, `publisher_id`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `user`, `book`.
     *
     * @param \App\QueryBuilders\ReviewBuilder $query
     * @param \App\Models\Review $review
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return ReviewResource
     */
    public function show(ReviewBuilder $query, Review $review): ReviewResource
    {
        return new ReviewResource($query->find($review->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific review resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam review required *integer* - No-example
     * The identifier of a specific review resource.
     *
     * @param \App\Http\Requests\ReviewSaveRequest $request
     * @param \App\Models\Review $review
     *
     * @return ReviewResource
     */
    public function update(ReviewSaveRequest $request, Review $review): ReviewResource
    {
        // start a transaction
        DB::beginTransaction();

        try {
            $review->fill($request->only($review->offsetGet('fillable')));

            if ($review->isDirty()) {
                $review->save();
            }

            // get rating of certain book
            $rating = Review::where('book_id', $review->book_id)
                ->avg('rating');

            // update book rating
            $book = Book::where('id', $review->book_id)->first();
            $book->rating = $rating;
            $book->save();

            return (new ReviewResource($review))
                ->additional(['info' => 'The review has been updated.']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => "an error occure when try to delete a reviews",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete Resource.
     * Delete a specific review resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam review required *integer* - No-example
     * The identifier of a specific review resource.
     *
     * @param \App\Models\Review $review
     *
     * @throws \Exception
     *
     * @return ReviewResource
     */
    public function destroy(Review $review)
    {
        // start a transaction
        DB::beginTransaction();

        try {
            if ($review->user_id !== auth()->user()->id) {
                return response()->json([
                    "message" => "you have no access to delete this reviews",
                ], 403);
            }

            $review->delete();

            // get rating of certain book
            $rating = Review::where('book_id', $review->book_id)
                ->avg('rating');

            // update book rating
            $book = Book::where('id', $review->book_id)->first();
            $book->rating = $rating ?? 0;
            $book->save();

            // update user rating
            $user = User::where('id', $review->user_id)->first();
            $user->reviews -= 1;
            $user->save();

            DB::commit();

            return (new ReviewResource($review))
                ->additional(['info' => 'The review has been deleted.']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => "an error occure when try to delete a reviews",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }
}
