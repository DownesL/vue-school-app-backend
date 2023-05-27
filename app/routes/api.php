<?php

use App\Http\Controllers\FiddleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\UserController;
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
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/test', [FiddleController::class, 'main']);

    Route::get('/user', [UserController::class, 'user']);


    Route::get('/groups', [GroupController::class, 'userGroups']);
    Route::get('/groups/not-joined', [GroupController::class, 'nonUserGroups']);
    Route::get('/groups/all', [GroupController::class, 'allGroups']);


    Route::get('/organisations', [OrganisationController::class, 'userOrgans']);
    Route::post('/organisations/', [OrganisationController::class, 'create']);
    Route::get('/organisations/all', [OrganisationController::class, 'allOrgans']);
    Route::get('/organisations/not-joined', [OrganisationController::class, 'nonUserOrgans']);
    Route::get('/organisations/{organisation}', [OrganisationController::class, 'organ'])
        ->whereNumber('organisation')
        ->can('view', 'organisation');
    Route::post('/organisations/{organisation}/messages', [MessageController::class, 'createMessage'])
        ->whereNumber('organisation')
        ->can('update', 'organisation');
    Route::post('/organisations/{organisation}/groups', [GroupController::class, 'create'])
        ->whereNumber('organisation')
        ->can('update', 'organisation');
    Route::post('organisations/{organisation}/join-request', [JoinRequestController::class, 'joinOrganisation'])
        ->whereNumber('organisation');


    Route::get('/messages', [MessageController::class, 'all']);
    Route::get('/messages/recent', [MessageController::class, 'recent']);
    Route::get('/messages/flagged', [MessageController::class, 'flagged']);
    Route::get('/messages/{message}', [MessageController::class, 'specific'])
        ->whereNumber('message')
        ->can('view', 'message');
    Route::post('/messages/{message}/flag', [MessageController::class, 'flag'])
        ->whereNumber('message')
        ->can('update', 'message');


    Route::get('/groups/{group}', [GroupController::class, 'group'])
        ->whereNumber('group');
    Route::post('/groups/{group}/attributes', [GroupController::class, 'setAttributes'])
        ->whereNumber('group')
        ->can('update', 'group');
    Route::post('groups/{group}/join-request', [JoinRequestController::class, 'joinGroup'])
        ->whereNumber('group');


    Route::put('join-requests/{joinRequest}/accept', [JoinRequestController::class, 'acceptRequest'])
        ->whereNumber('joinRequest')
        ->can('update', 'joinRequest');
    Route::put('join-requests/{joinRequest}/reject', [JoinRequestController::class, 'denieRequest'])
        ->whereNumber('joinRequest')
        ->can('update', 'joinRequest');
});

