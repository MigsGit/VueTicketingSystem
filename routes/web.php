<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
Route::any('users/{id}', function ($id) {

});
Route::middleware('CheckHasNoSession')->group(function(){
    Route::view('/', 'main');
    Route::view('/unauthorized', 'main');
});
Route::middleware('CheckHasSession')->group(function(){
    Route::get('/panel_template/{any}', function(){
        return view('main');
    })->where('any','.*');
});



Route::post('/login',[AuthController::class, 'login']);
Route::get('/logout',[AuthController::class, 'logout']);
Route::get('/check_ses',[AuthController::class, 'check_ses']);
