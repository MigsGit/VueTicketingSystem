<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TRTController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('check_ses',[AuthController::class, 'check_ses']);
    Route::get('get_tickets', [TicketController::class, 'get_tickets']);
    Route::post('save_ticket', [TicketController::class, 'save_ticket']);
    Route::get('get_ticket_info', [TicketController::class, 'get_ticket_info']);

    Route::post('closing_ticket', [TicketController::class , 'closingTicket'])->name('closing_ticket');
    Route::get('read_resolution_by_user', [TicketController::class , 'readResolutionByUser'])->name('read_resolution_by_user');
    Route::get('read_resolution_title_by_id', [TicketController::class , 'readResolutionTitleById'])->name('read_resolution_title_by_id');
    Route::post('create_new_resolution', [TicketController::class , 'createNewResolution'])->name('create_new_resolution');
    Route::get('get_assigned_tickets', [TicketController::class , 'getAssignedTickets'])->name('get_assigned_tickets');

    Route::get('get_user_info', [UserController::class , 'getUserInfo'])->name('get_user_info');
    Route::get('read_user_info', [UserController::class , 'readUserInfo'])->name('read_user_info');
    Route::post('save_user_info', [UserController::class , 'saveUserInfo'])->name('save_user_info');

    Route::post('deact_trt', [TRTController::class , 'deact_trt'])->name('deact_trt');
    Route::post('save_trt', [TRTController::class , 'save_trt'])->name('save_trt');
    Route::get('get_trt', [TRTController::class , 'get_trt'])->name('get_trt');
    Route::get('get_trt_for_edit', [TRTController::class , 'get_trt_for_edit'])->name('get_trt_for_edit');
    Route::get('get_trt_option', [TRTController::class , 'get_trt_option'])->name('get_trt_option');

    Route::get('read_resolution_by_user_setting', [SettingController::class , 'readResolutionByUserSetting'])->name('read_resolution_by_user_setting');

    Route::get('get_user_option',[UserController::class, 'getUserOption'])->name('get_user_option');

});
