<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Api\ApiController;
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


Route::post('/update-pending-order/{id}', [ApiController::class, 'updatePendingOrder']);
Route::get('/update-pending-order-test/{id}', [ApiController::class, 'updatePendingOrder']);

Route::get('/update-initial-amount', [ApiController::class, 'updateInitialAmount']);


//  $homeControl->trnsectionDeatil($user->id, $user, '', '', 1);
