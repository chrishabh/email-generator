<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PaymentController;

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
    return view('index');
});
Route::get('/single-verification', function () {
    return view('single-verification');
});

Route::get('/bulk-verification', function () {
    return view('bulk-verification');
});
Route::get('/signup', function () {
    return view('signup');
});

Route::get('/payment', function () {
    return view('payment');
});

Route::get('/signin', function () {
    return view('signin');
});

Route::get('/create-order', [PaymentController::class, 'createOrder']);
Route::post('/handle-payment', [PaymentController::class, 'handlePayment']);


// Route::post('/generate-email', [EmailController::class, 'generateEmail'])->name('generateEmail');
