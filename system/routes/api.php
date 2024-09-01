<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Barber\BarberController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\ServiceType\ServiceTypeController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('barber')->group(function () {
        Route::controller(BarberController::class)->group(function () {
            Route::post('/update', 'update');
        });
    });

    Route::prefix('service-type')->group(function () {
        Route::controller(ServiceTypeController::class)->group(function () {
            Route::get('/index', 'index');
            Route::post('/create', 'create');
            Route::post('/delete/{id}', 'delete');
            Route::post('/update', 'update');
        });
    });

    Route::prefix('customer')->group(function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::post('/update', 'update');
            Route::post('/get-available-times-of-barber', 'GetAvailableTimesOfBarber');
        });
    });

    Route::post('/logout', [AuthenticationController::class, 'logout']);
});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/create-customer', [CustomerController::class, 'create']);
Route::post('/create-barber', [BarberController::class, 'create']);