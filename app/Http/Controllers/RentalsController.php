<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalSaveRequest;
use App\Http\Resources\RentalCollection;
use App\Http\Resources\RentalResource;
use App\Jobs\OverDueRentalJob;
use App\Mail\RentalCreateEmailNotification;
use App\Models\Book;
use App\Models\Rental;
use App\QueryBuilders\RentalBuilder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
    protected static $requireAuthorization = false;

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
        return new RentalCollection($query->getUserRental(auth()->user()->id, 5));
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
        // start a transaction
        DB::beginTransaction();

        try {
            $rental->fill($request->only($rental->offsetGet('fillable')));

            // get book 
            $book = Book::where('id', $rental->book_id)->first();

            // check if book is available
            if ($book->available === 0) {
                return response()->json([
                    "message" => "book is not available right now!!!",
                ], 200);
            }

            // check if user has already rented this book
            $dummyRent = Rental::where('user_id', auth()->user()->id)
                ->where('book_id', $rental->book_id)
                ->first();

            if ($dummyRent) {
                return response()->json([
                    "message" => "the user has already rented this book.",
                ], 400);
            }

            // fill new rental data 
            $rental->user_id = auth()->user()->id;
            $rental->rental_date = Carbon::now('Asia/Jakarta');
            $rental->rental_duration = 7;
            $rental->status = Rental::STATUS_BORROWED;
            $rental->save();

            // decrement book count
            $book->available -= 1;
            $book->save();

            // Increment the "rentals" column by 1
            $user = auth()->user();
            $user->rentals += 1;
            $user->save();

            DB::commit();

            $resource = (new RentalResource($rental))
                ->additional(['info' => 'The new rental has been saved.']);

            // send email notification to user when successfully create a new rental
            dispatch(function () use ($rental) {
                Mail::to(auth()->user()->email)->send(new RentalCreateEmailNotification($rental->load("book")->load("user")));
            });

            // create a job with delay 7 days
            dispatch(new OverDueRentalJob($rental->id))->delay(now()->addDay(7));

            return $resource->toResponse($request)->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => "an error occure when try to create a rentals",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return Books.
     * Update Rentals Resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\RentalSaveRequest $request
     * @param \App\Models\Rental $rental
     *
     * @return JsonResponse
     */
    public function return(RentalSaveRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $rental = Rental::where('book_id', $request->book_id)->where("user_id", auth()->user()->id)->first();
            // dd($rental);

            if (!$rental) {
                return response()->json([
                    "message" => "you have no rental this book yet",
                ], 400);
            }

            if ($rental->status === Rental::STATUS_RETURNED) {
                return response()->json([
                    "message" => "you have already returned this book",
                ], 400);
            }

            $now = Carbon::now('Asia/Jakarta');
            $rentalDate = Carbon::parse($rental->rental_date, 'Asia/Jakarta');
            $due_date = $rentalDate->copy()->addDays($rental->rental_duration);

            $rental->status = Rental::STATUS_RETURNED;
            $rental->return_date = $now;

            $msg = [
                'info' => 'The book has been returned successfully'
            ];

            if ($now->gt($due_date)) {
                $rental->is_due = true;

                $msg['alert'] = "Your rental period has ended, and to complete the process, you must pay the late fee charges.";
                $msg["due_date"] = $due_date->format('Y-m-d h:i A');

                // ... fee charge logic.. soon
            }

            // increment book count
            $book = Book::where('id', $rental->book_id)->first();
            $book->available -= 1;
            $book->save();

            // save rental data to db
            $rental->save();

            // change rental users
            $user = auth()->user();
            $user->rentals += 1;
            $user->save();

            DB::commit();

            $resource = (new RentalResource($rental))
                ->additional($msg);

            return $resource->toResponse($request)->setStatusCode(200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => "an error occure when try to return a book",
                "errors" => $e->getMessage(),
            ], 500);
        }
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
