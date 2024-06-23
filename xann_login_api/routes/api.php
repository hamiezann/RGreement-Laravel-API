<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAccessController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RentHouse;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\IssueController;
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
Route::get('/tenants', [TenantController::class, 'index']);
Route::put('/tenants/{id}', [TenantController::class, 'update']);
Route::post('issues/create', [IssueController::class, 'store']);
Route::get('/tenant-by-house/{houseId}', [TenantController::class, 'getTenantByHouseId']);

//  Renter
Route::get('/house-details/{id}', [RentHouse::class, 'getRentHousesById']);
Route::get('/find-house/{houseId}', [RentHouse::class, 'findHouseById']);
Route::post('/compare-identifier', [RentHouse::class, 'compareIdentifier']);
Route::post('/apply-rent-house', [TenantController::class, 'store']);
Route::get('/applied-houses/{id}', [TenantController::class, 'getAppliedHouses']);
Route::get('/get-UniIdentifier/{houseId}', [RentHouse::class, 'getUniIdentifier']) ;
Route::put('/sign-now/{houseId}', [TenantController::class, 'findByHouseId']);
Route::post('/users/{userId}/edit-profile', [UserAccessController::class, 'updateProfile']);
Route::get('/users/{userId}/profile', [UserAccessController::class, 'getProfile']);
Route::post('/users/{userId}/change-password', [UserAccessController::class, 'changePassword']);
Route::get('houses/{house_id}/issues', [IssueController::class, 'viewByHouseId']);
Route::put('issues/{id}/status', [IssueController::class, 'updateStatus']);
// Route::patch('issues/{id}/re', [IssueController::class, 'approveDepositRelease']);
// Route::patch('issues/{id}/status', [IssueController::class, 'rejectDepositRelease']);

//  Message Pathway
Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/messages/{id}', [MessageController::class, 'show']);
Route::put('/messages/{id}', [MessageController::class, 'update']);
Route::delete('/messages/{id}', [MessageController::class, 'destroy']);

Route::get('/conversations', [MessageController::class, 'conversation_list']);
// Route::get('/conversations', [MessageController::class, 'conversation_count']);
Route::get('/conversations/{senderId}/{recipientId}', [MessageController::class, 'conversation_show']);



