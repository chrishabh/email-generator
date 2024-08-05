<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
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

Route::middleware('guest')->group(function(){
    Route::get('/signup',[RegisterController::class,'showRegistrationForm'])->name('signup');
    Route::post('/signup',[RegisterController::class,'signup']); 
    Route::get('/signin',[LoginController::class,'showLoginForm'])->name('signin');
    Route::post('/signin',[LoginController::class,'login']);
});
 
Route::get('/logout',[LogoutController::class,'logout'])->name('logout');
Route::get('/single-verification', function () {
    return view('single-verification');
});

Route::get('/bulk-verification', function () {
    return view('bulk-verification');
});
// Route::get('/signup', function () {
//     return view('auth/signup');
// });

// Route::get('/payment', function () {
//     return view('payment');
// });

// Route::get('/signin', function () {
//     return view('auth/signin');
// });

Route::post('/create-order', [PaymentController::class, 'createOrder'])->name('create.Order');;
Route::post('/handle-payment', [PaymentController::class, 'handlePayment'])->name('handlePayment');;


// Route::post('/generate-email', [EmailController::class, 'generateEmail'])->name('generateEmail');
