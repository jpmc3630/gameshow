<?php

use App\Events\TestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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


Route::get('/socket', function (Request $request) {
  event(new TestEvent('hello world'));
  return 'oki';
});


Route::middleware('auth:sanctum')->get('/new-room', [LoginController::class, 'newRoom']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
  return 'Hello!';
});

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');

Route::post('/register', [LoginController::class, 'register'])
    ->name('register');

Route::post('/thetoken', function (Request $request) {
    \Log::info($request['the_token']);
    return 'Hello!';
});
