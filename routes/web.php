<?php

use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\User\LoginHistoryController;
use App\Http\Controllers\Admin\User\PasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthCenterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\User\UserAccountController;
use Illuminate\Support\Facades\Artisan;
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

//forget password
Route::group(['prefix' => 'forgot-password', 'middleware' => ['guest']], function () {
    Route::get('/', [AuthenticatedSessionController::class, 'forgetPassword'])->name('forgot-password');
    Route::post('/store', [AuthenticatedSessionController::class, 'storeForgetPassword'])->name('forgot-password.store');
});

//Reset Password
Route::group(['prefix' => 'reset-password', 'middleware' => ['guest']], function () {
    Route::get('/{code}', [AuthenticatedSessionController::class, 'resetPassword'])->name('reset-password');
    Route::post('/store/{code}', [AuthenticatedSessionController::class, 'storeResetPassword'])->name('reset-password.store');
});

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
        Route::get('/{id}', [CustomerController::class, 'detail'])->name('customers.detail');
        Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');
        Route::patch('/{id}/update', [CustomerController::class, 'update'])->name('customers.update');
        Route::get('/ajax/list', [CustomerController::class, 'ajaxLists'])->name('customers.lists');
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

    Route::group(['prefix' => 'user', 'middleware' => ['role:Admin']], function() {
        Route::patch('/{id}/change-password', [PasswordController::class, 'update'])->name('users.changePassword');
        Route::get('/{id}/fetch/history', [LoginHistoryController::class, 'index'])->name('users.histories');
    });

    Route::group(['prefix' => 'settings','middleware' => ['role:Admin']], function () {
        Route::get('/',[SettingController::class, 'index'])->name('settings.index');
        Route::post('/',[SettingController::class, 'store'])->name('settings.store');
    });
});

Route::group(['prefix' => 'system'], function() {
   Route::get('/cache-clear', function() {
       Artisan::call('optimize:clear');
   });

   Route::get('/migrate', function() {
       Artisan::call('migrate --force');
   });
});
