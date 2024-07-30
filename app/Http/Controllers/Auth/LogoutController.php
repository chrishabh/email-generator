<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try { 
            if(Auth::check()){ 
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();  
            }   
            return redirect('/');
        } catch (Exception $e) { 
            Log::error('Logout failed: ' . $e->getMessage());
        }
    }
}
