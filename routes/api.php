<?php

use App\Http\Controllers\ServiceData\ServiceDataController;
use Illuminate\Http\Request;
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



Route::get('v1/balance/Utn', [ServiceDataController::class, 'balance']);
Route::get('v1/product/Utn', [ServiceDataController::class, 'product']);
