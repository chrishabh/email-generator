<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now  create something great!
|
*/
try{

    
    
    Route::middleware(['guest','session.timeout'])->group(function(){

        Route::get('/', function () {
            return view('index');
        });
    
        Route::get('/signup',[RegisterController::class,'showRegistrationForm'])->name('signup');
        Route::post('/signup',[RegisterController::class,'signup']); 
        Route::get('/signin',[LoginController::class,'showLoginForm'])->name('signin');
        Route::post('/signin',[LoginController::class,'login']);
        Route::get('/recovery',[LoginController::class,'showResetForm'])->name('recovery');
        Route::post('/recovery',[LoginController::class,'resetPassword']); 
        Route::get('/single-verification', function () {
            return view('single-verification');
        });
             
        Route::get('/bulk-verification', function () {
            return view('bulk-verification');
        });


           
    });

    Route::middleware(['restrict.access','session.timeout'])->group(function(){
        Route::get('/single',[EmailController::class,'singleEmailPage'])->name('single');
        Route::post('/check-email',[EmailController::class,'checkEmailIsValidInvalid'])->name('check-email');
        Route::get('/lead-finder',[EmailController::class,'leadFinder'])->name('lead-finder');
        Route::post('/lead-finder',[EmailController::class,'generateEmail']);
        Route::post('/email-verification',[EmailController::class,'emailVerification'])->name('email-verification');
        Route::get('/bulk', [EmailController::class,'bulkPage'])->name('bulk');
        Route::post('/bulk', [EmailController::class,'searchBar']);
        Route::post('/upload', [EmailController::class, 'uploadBulkData']);
        Route::get('/check-file-status', [EmailController::class, 'getAllData']);
        Route::post('/export-data', [EmailController::class, 'exportData']);
        Route::post('/start-verification', [EmailController::class, 'startVerification']);
        Route::get('/verification', function () {
            return view('verify');
        });
        Route::get('/profile', function () {
            return view('comming-soon');
        });
      

        Route::middleware('auth:web')->group(function(){
            Route::post('/create-order', [PaymentController::class, 'createOrder'])->name('create.Order');
            Route::post('/handle-payment', [PaymentController::class, 'handlePayment'])->name('handlePayment');
            Route::post('/verification-code', [LoginController::class, 'verification'])->name('verification.code');
            Route::get('/pricing', [PaymentController::class, 'getPricing'])->name('pricing');
            Route::get('/resend-code', [LoginController::class, 'resendCode'])->name('resend.code');
            Route::get('/payment-history', [PaymentController::class, 'getPaymentHistory'])->name('payment.history');
      
        });
         
        
        // Route::get('/profile',[ProfileController::class,'getProfilePage']);

    });

    Route::get('/logout',[LogoutController::class,'logout'])->name('logout');

    
    // Route::post('/revert', [FileUploadController::class, 'revert']);
    // Route::get('/load', [FileUploadController::class, 'load']);

 

}catch (\Exception $e) {
    return view('something-went-wrong');
}

// Route::fallback(function () {
//     return view('route-not-found');
// });
