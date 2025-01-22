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
    Route::get('check_ses',[AuthController::class, 'check_ses']);

    Route::controller(TicketController::class)->group(function () {
        Route::get('get_tickets', 'get_tickets');
        Route::post('save_ticket', 'save_ticket');
        Route::get('get_ticket_info', 'get_ticket_info');
        Route::post('closing_ticket' , 'closingTicket')->name('closing_ticket');
        Route::get('read_resolution_by_user' , 'readResolutionByUser')->name('read_resolution_by_user');
        Route::get('read_resolution_title_by_id' , 'readResolutionTitleById')->name('read_resolution_title_by_id');
        Route::post('create_new_resolution' , 'createNewResolution')->name('create_new_resolution');
        Route::get('get_assigned_tickets' , 'getAssignedTickets')->name('get_assigned_tickets');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('get_user_info', 'getUserInfo')->name('get_user_info');
        Route::get('read_user_info', 'readUserInfo')->name('read_user_info');
        Route::post('save_user_info', 'saveUserInfo')->name('save_user_info');
        Route::get('get_user_option','getUserOption')->name('get_user_option');
    });

    Route::controller(TRTController::class)->group(function () {
        Route::post('deact_trt', 'deact_trt')->name('deact_trt');
        Route::post('save_trt', 'save_trt')->name('save_trt');
        Route::get('get_trt', 'get_trt')->name('get_trt');
        Route::get('get_trt_for_edit', 'get_trt_for_edit')->name('get_trt_for_edit');
        Route::get('get_trt_option', 'get_trt_option')->name('get_trt_option');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('read_resolution_by_user_setting', 'readResolutionByUserSetting')->name('read_resolution_by_user_setting');
    });
});
