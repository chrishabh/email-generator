<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileUploadController;
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
try{

    Route::get('/', function () {
        return view('index');
    });
    
    Route::middleware('guest')->group(function(){
            Route::get('/signup',[RegisterController::class,'showRegistrationForm'])->name('signup');
            Route::post('/signup',[RegisterController::class,'signup']); 
            Route::get('/signin',[LoginController::class,'showLoginForm'])->name('signin');
            Route::post('/signin',[LoginController::class,'login']); 
    });
    
    Route::middleware('auth:web')->group(function(){
            Route::post('/create-order', [PaymentController::class, 'createOrder'])->name('create.Order');
            Route::post('/handle-payment', [PaymentController::class, 'handlePayment'])->name('handlePayment');
            Route::post('/verification-code', [LoginController::class, 'verification'])->name('verification.code');
      
    });
     
    Route::get('/logout',[LogoutController::class,'logout'])->name('logout');


    Route::post('/upload', [EmailController::class, 'uploadBulkData']);
    Route::post('/revert', [FileUploadController::class, 'revert']);
    Route::get('/load', [FileUploadController::class, 'load']);

    Route::get('/single-verification', function () {
        return view('single-verification');
    });
    
    Route::get('/single',[EmailController::class,'singleEmailPage'])->name('single');
    Route::post('/single',[EmailController::class,'generateEmail']);
    
    Route::get('/bulk', [EmailController::class,'bulkPage'])->name('bulk');
    Route::get('/bulk-verification', function () {
        return view('bulk-verification');
    });

    Route::get('/verification', function () {
        return view('verify');
    });
 

}catch (\Exception $e) {
    return view('something-went-wrong');
}

Route::fallback(function () {
    return view('route-not-found');
});
