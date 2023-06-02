<?php

use App\Http\Controllers\UserController;
use App\Models\Message;
use App\Models\User;
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
Route::post('/api/login', [UserController::class, 'login'])->name('login');

Route::post('/api/logout', [UserController::class, 'logout']);

Route::post('/api/register', [UserController::class, 'register']);

