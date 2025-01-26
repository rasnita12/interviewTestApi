<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthCenterController;
use App\Http\Controllers\User\UserAccountController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function() {
    return redirect()->route('login');
});

// Auth
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

//logout
Route::any('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//register
Route::get('register', [RegisterController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:Admin')->name('dashboard');

    Route::get('/account', [UserAccountController::class, 'index'])->middleware('role:Customer')->name('user.account');

    Route::get('/account/health-centers', [HealthCenterController::class, 'index'])->middleware('role:Customer')->name('get.address');


    //customers
    Route::group(['prefix' => 'customers', 'middleware' => ['role:Admin']], function() {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/ajax/list', [CustomerController::class, 'ajaxList'])->name('customers.lists');
        Route::get('/{id}/fetch/history', [CustomerController::class, 'fetchHistory'])->name('customers.history');
    });

    Route::group(['prefix' => 'health-centers', 'middleware' => ['role:Admin']], function() {
        Route::get('/', [HealthCenterController::class, 'get'])->name('health-centers.index');
        Route::get('/ajax/list', [HealthCenterController::class, 'ajaxList'])->name('health-centers.lists');
        Route::post('/store', [HealthCenterController::class, 'store'])->name('health-centers.store');
        Route::get('/create', [HealthCenterController::class, 'create'])->name('health-centers.create');
        Route::get('/{id}/edit', [HealthCenterController::class, 'edit'])->name('health-centers.edit');
        Route::patch('/{id}/update', [HealthCenterController::class, 'update'])->name('health-centers.update');
        Route::delete('/{id}/destroy', [HealthCenterController::class, 'delete'])->name('health-centers.delete');
    });

    Route::group(['prefix' => 'health-centers', 'middleware' => ['role:Customer']], function() {
        Route::get('/search-health-center', [HealthCenterController::class,'loadHealthCenterViewPage'])->name('load-health-center');

    });

});
