<?php

use App\Http\Controllers\UIControllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UIControllers::class, 'index'])->name('index');
// Route::post('/', [UIControllers::class, 'store'])->name('store');
// Route::get('/matrix/{id}/edit', [UIControllers::class, 'edit'])->name('edit');
// Route::delete('/delete/{id}', [UIControllers::class, 'destroy'])->name('delete');
// Route::put('/matrix', [UIControllers::class, 'update'])->name('update');
