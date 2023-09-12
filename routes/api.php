<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
Route::get('get_user_info', [UserController::class , 'getUserInfo'])->name('get_user_info');
Route::get('read_user_info', [UserController::class , 'readUserInfo'])->name('read_user_info');
Route::post('save_user_info', [UserController::class , 'saveUserInfo'])->name('save_user_info');
Route::group(['middleware' => ['auth:sanctum']], function () {
});
