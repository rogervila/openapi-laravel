<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/pet')->group(function () {
    Route::post('/', \App\Http\Controllers\Pet\StorePetController::class);
    Route::put('/', \App\Http\Controllers\Pet\UpdatePetController::class);
    Route::get('/findByStatus', \App\Http\Controllers\Pet\FindPetsByStatusController::class);
    Route::get('/findByTags', \App\Http\Controllers\Pet\FindPetsByTagsController::class);
    Route::get('/{petId}', \App\Http\Controllers\Pet\FindPetByIdController::class);
    Route::delete('/{petId}', \App\Http\Controllers\Pet\DeletePetController::class);
});
