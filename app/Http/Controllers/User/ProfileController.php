<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    function getProfilePage(Request $request){
        return view('personal.profile');
    }
}
