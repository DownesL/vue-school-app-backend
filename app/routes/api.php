<?php

use App\Http\Controllers\FiddleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrganisationController;
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

//Route::get('/', [FiddleController::class,'main']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/test', [FiddleController::class, 'main']);

    Route::get('/user',[UserController::class, 'user']);
    Route::get('/groups', [GroupController::class, 'userGroups']);
    Route::get('/groups/not-joined', [GroupController::class, 'nonUserGroups']);
    Route::get('/groups/all', [GroupController::class, 'allGroups']);
    Route::get('/groups/{id}', [GroupController::class, 'group'])->whereNumber('id');

    Route::get('/organisations', [OrganisationController::class, 'userOrgans']);
    Route::get('/organisations/not-joined', [OrganisationController::class, 'nonUserOrgans']);
    Route::get('/organisations/all', [OrganisationController::class, 'allOrgans']);
    Route::get('/organisations/{id}', [OrganisationController::class, 'organ'])->whereNumber('id');

    Route::get('/messages/unread', [MessageController::class, 'unread']);
    Route::get('/messages/{id}', [MessageController::class, 'specific'])->whereNumber('id');
    Route::get('/messages/flagged', [MessageController::class, 'flagged']);
});
    Route::get('/messages', [MessageController::class, 'all']);

