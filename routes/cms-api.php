<?php

use App\Http\Controllers\BooksController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register CMS API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:cms-api')->group(static function () {
    Route::get('/current-admin/profile', 'CurrentAdminController@show')->name('current-admin.show');
    Route::put('/current-admin/profile', 'CurrentAdminController@update')->name('current-admin.update');

    Route::apiResource('/permissions', 'PermissionsController');
    Route::apiResource('/roles', 'RolesController');
    Route::apiResource('/settings', 'SettingsController');
    Route::apiResource('/cms_admins', 'CmsAdminsController');
    Route::apiResource('/seo_metas', 'SeoMetasController');
});

Route::apiResource('/books', 'BooksController');
Route::apiResource('/authors', 'AuthorsController');
Route::apiResource('/publishers', 'PublishersController');

Route::apiResource('/reviews', 'ReviewsController');
