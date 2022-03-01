<?php

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

Route::get('/test', function () {
    $test = "テスト";
    $debug = "デバッグ";
    $testOfDebug = "${debug}の${test}用です！！！";
    var_dump($testOfDebug);
});

Route::middleware(['auth'])->group(function() {
    Route::get('payment/stripe/status', 'App\Http\Controllers\Payment\Stripe\StripeController@status');
    Route::post('payment/stripe/subscribe', 'App\Http\Controllers\Payment\Stripe\StripeController@subscribe');
    Route::post('payment/stripe/cancel', 'App\Http\Controllers\Payment\Stripe\StripeController@cancel');
    Route::post('payment/stripe/resume', 'App\Http\Controllers\Payment\Stripe\StripeController@resume');
    Route::post('payment/stripe/change_plan', 'App\Http\Controllers\Payment\Stripe\StripeController@change_plan');
    Route::post('payment/stripe/update_card', 'App\Http\Controllers\Payment\Stripe\StripeController@update_card');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
