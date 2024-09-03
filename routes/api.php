<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PaymentController;
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


Route::get('/getVerify',function(){
    // VerifyEmailsJob::dispatch();
    ExportVerifiedEmailsJob::dispatch(7);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('email-generator',[EmailController::class, 'generateEmail'])->name('generateEmail');
Route::get('test-api',[EmailController::class, 'testThirdPartyAPI'])->name('testThirdPartyAPI');


