<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicEmailVerifications\EmailVerificationController;
use App\Http\Middleware\VerifyApiKeyAndCredits;
use App\Jobs\ExportVerifiedEmailsJob;
use App\Jobs\VerifyEmailsJob;

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

Route::get('/lead',[EmailController::class,'generateEmail']);
Route::get('/getVerify',[EmailController::class,'generateEmail']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['verify.apikey.credits'])->get('/v1/verify', [EmailVerificationController::class, 'verifyEmail'])->name('verifyEmail');

// Route::middleware(['public.auth'])->get('/v1/creditInfo', [EmailVerificationController::class, 'creditInfo'])->name('creditInfo');

Route::group(['middleware' => ['public.auth']], function (){
    Route::get('/v1/creditInfo',[EmailVerificationController::class, 'creditInfo'])->name('creditInfo'); 
    Route::post('v1/bulk', [EmailVerificationController::class, 'bulkVerify'])->name('bulkVerify');
    Route::post('v1/bulk/start-verification', [EmailVerificationController::class, 'startBulkVerify'])->name('startBulkVerify'); 
    Route::get('/v1/bulk/{jobId?}', [EmailVerificationController::class, 'chkJob']);
    Route::delete('/v1/bulk/{jobId?}', [EmailVerificationController::class, 'deleteJob'])->name('deleteJob');
});

Route::fallback(function (Request $request) {
    return response()->json([
        'success' => false,
        'result' => 'Route not found',
        'code'  => 404
    ], 404);
});

Route::post('email-generator',[EmailController::class, 'generateEmail'])->name('generateEmail');
Route::get('test-api',[EmailController::class, 'testThirdPartyAPI'])->name('testThirdPartyAPI');
Route::post('smtp-handshake',[EmailController::class, 'smtpHandshake'])->name('smtpHandshake');

