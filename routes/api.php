<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**Article routes */
Route::get('/post', [ArticleController::class, 'index'])->middleware("auth:api");
Route::post('/post', [ArticleController::class, 'store'])->middleware("auth:sanctum");
Route::get('/post/{id}', [ArticleController::class, 'show']);
Route::delete('/post/{id}', [ArticleController::class, 'destroy']);
Route::put('/post/{id}', [ArticleController::class, 'update']);

/**Author routes */
Route::group(["middleware" => "auth:sanctum"], function () {
    Route::post('/logout', [AuthorController::class, 'logout']);
    Route::get('/author/detail', [AuthorController::class, 'getAuthor']);
    Route::get('/author/articles', [AuthorController::class, 'getAuthorPost']);
});


Route::post('/login', [AuthorController::class, 'login'])->name('login');

Route::post('/register', [AuthorController::class, 'register']);
