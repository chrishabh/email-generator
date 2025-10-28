<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\UserCredits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();


           // Step 1: Check if a user with this Google ID already exists
           $user = User::where('google_id', $googleUser->id)->first();
        //    pp($googleUser->id);

           // Step 2: If not, check if email already exists
           if (!$user) {
               $existingUser = User::where('email', $googleUser->email)->first();

               if ($existingUser) {
                   // Link Google account to existing user
                   $existingUser->update([
                       'google_id' => "$googleUser->id",
                       'avatar' => "$googleUser->avatar",
                   ]);
                   $user = $existingUser;
                } else {
                   // Create new user
                   $user = User::create([
                       'name' => $googleUser->name,
                       'email' => $googleUser->email,
                       'google_id' => $googleUser->id,
                       'avatar' => $googleUser->avatar,
                       'password' => bcrypt(Str::random(16)),
                       'email_verified'=>'1', // random password
                       'email_verified_at' => now(),
                   ]);
                   UserCredits::initialFreeCredit(User::getUserId($googleUser->email));
                }
            }

            Auth::login($user,true);
            request()->session()->regenerate(); 
            session(['lastActivityTime'=>time()]);
            session(['showLowCreditPopup'=>true]);
            return  redirect()->intended('/single'); // change to wherever user should land
        } catch (Exception $e) {
            return redirect('/signin')->with('error', 'Google login failed, please try again.');
        }
    }
}
