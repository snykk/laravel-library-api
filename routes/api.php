<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix("/auth")->group(function () {
  Route::post("/login", "AuthController@login");
  Route::post("/logout", "AuthController@login")->middleware("auth:santcum");
});

Route::apiResource('/books', 'BooksController');
Route::apiResource('/authors', 'AuthorsController');
Route::apiResource('/publishers', 'PublishersController');

Route::get("/reviews/my-review", "ReviewsController@userReview")->middleware("auth:sanctum");
Route::apiResource('/reviews', 'ReviewsController')->middleware("auth:sanctum");
