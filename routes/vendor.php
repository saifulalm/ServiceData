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


Route::get('balance/utn', [ServiceDataController::class, 'balance']);
Route::get('product/utn', [ServiceDataController::class, 'product']);
Route::get('utn', [ServiceDataController::class, 'index']);



