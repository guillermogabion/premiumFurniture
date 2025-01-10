<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomInboxController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StudentClassroomController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\SendSMSController;
use App\Models\ClassroomInbox;
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

Route::post('/createaccount', [UsersController::class, 'createaccount']);
Route::post('/login', [UsersController::class, 'login']);
Route::post('/logout', [UsersController::class, 'logout'])->middleware('auth:api');
Route::get('/send-sms', [SendSMSController::class, 'send2FAMessage']);

Route::middleware('auth:api')->group(function () {
    Route::get('/validate-token', [UsersController::class, 'validateToken']);
    Route::get('/self', [UsersController::class, 'self']);
    Route::get('/entity', [UsersController::class, 'getEntities']);
    Route::put('/update-password', [UsersController::class, 'updatePassword']);
    Route::post('/validate-token', [TokenController::class, 'validateToken']);


    Route::get('/details', [DetailsController::class, 'index']);

    Route::get('/show-class', [ClassroomController::class, 'showAll']);
    Route::post('/add-class', [ClassroomController::class, 'store']);

    // classroom 
    Route::get('/show-class-students', [StudentClassroomController::class, 'getStudents']);
    Route::delete('/delete-class-students', [StudentClassroomController::class, 'delete']);
    Route::put('/class-students/{id}/update', [StudentClassroomController::class, 'updateStatus']);

    Route::get('/get-my-room', [StudentClassroomController::class, 'getMyRoom']);
    Route::post('/store-class-students', [StudentClassroomController::class, 'store']);

    Route::get('/get-all-message/{classroomId}/inbox', [ClassroomInboxController::class, 'index']);
    Route::post('/send-message', [ClassroomInboxController::class, 'send']);
});
