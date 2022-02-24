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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('user')->middleware(['auth'])->group(function() {
    // 課金
    Route::get('payment/stripe/status', 'Payment\Stripe\StripeController@status');
    Route::post('payment/stripe/subscribe', 'Payment\Stripe\StripeController@subscribe');
    Route::post('payment/stripe/cancel', 'Payment\Stripe\StripeController@cancel');
    Route::post('payment/stripe/resume', 'Payment\Stripe\StripeController@resume');
    Route::post('payment/stripe/change_plan', 'Payment\Stripe\StripeController@change_plan');
    Route::post('payment/stripe/update_card', 'Payment\Stripe\StripeController@update_card');
});