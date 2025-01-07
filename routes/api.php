<?php

use App\Http\Controllers\MatrixController;
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


Route::prefix('matrix')->group(function() {
    Route::post('/', [MatrixController::class, 'store']);           // CREATE
    Route::put('{id}', [MatrixController::class, 'update']);         // UPDATE
    Route::delete('{id}', [MatrixController::class, 'destroy']);     // DELETE
    Route::get('{skip}/{take}', [MatrixController::class, 'index']); // READ - LIST
    Route::get('{id}', [MatrixController::class, 'show']);        // READ - SINGLE by ID
});