<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UrlController::class, 'index']);
Route::post('/submit-form', [UrlController::class, 'SubmitForm'])->name('submit-form');
Route::post('/hit-count', [UrlController::class, 'HitCount'])->name('hit-count');
