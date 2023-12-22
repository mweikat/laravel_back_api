<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    function register(Request $request) {
 
        $user           = new User();
        $user->name     = $request->name;
        $user->last_name= $request->last_name;
        $user->email    = $request->email;

        $passHash = Hash::make($request->password);

        $user->password = $passHash; 
        $user->save();
        
        $user->sendEmailVerificationNotification();
        
   
        return response('',201); 
     } 
}
