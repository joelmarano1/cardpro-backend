<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/signup', [UserController::class,'register_user']);
Route::post('/login', [UserController::class,'login']);
Route::post('/save_card', [CardController::class,'save_card']);
Route::get('/shared_card/{id}', [CardController::class,'shared_card']);
Route::post('/get_card_list', [CardController::class,'get_card_list']);
Route::post('/get_card_social_links', [CardController::class,'get_card_social_links']);




