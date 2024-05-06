<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAccessController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RentHouse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//  Authentication
Route::post('register',[UserAccessController::class,'register']);
Route::post('login',[UserAccessController::class,'login']);
Route::post('logout',[UserAccessController::class,'logout'])
  ->middleware('auth:sanctum');

//  Landlord
Route::post('house-details',[RentHouse::class,'store']);
Route::get('/list/{userId}/rent-houses', [RentHouse::class, 'getRentHousesByUser']);
Route::delete('/delete-rent-houses/{id}', [RentHouse::class, 'destroy']);
Route::put('/update-rent-house/{id}', [RentHouse::class, 'update']);
Route::get('/nearby-house-list', [RentHouse::class, 'list']);

//  Renter
Route::get('/house-details/{id}', [RentHouse::class, 'getRentHousesById']);

//  Message Pathway
Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/messages/{id}', [MessageController::class, 'show']);
Route::put('/messages/{id}', [MessageController::class, 'update']);
Route::delete('/messages/{id}', [MessageController::class, 'destroy']);



