<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\AccountController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => '/customers'], function(){
    Route::get('', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

Route::group(['prefix' => '/accounts'], function(){
    Route::get('', [AccountController::class, 'index'])->name('account.index');
    Route::post('', [AccountController::class, 'store'])->name('account.store');
    Route::get('/{id}', [AccountController::class, 'show'])->name('account.show');
    Route::put('/{id}', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::post('/deposit', [AccountController::class, 'deposit'])->name('account.deposit');
    Route::post('/withdraw', [AccountController::class, 'withdraw'])->name('account.withdraw');
});
