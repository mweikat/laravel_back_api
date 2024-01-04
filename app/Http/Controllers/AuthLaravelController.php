<?php

namespace App\Http\Controllers;

use Src\admin\user\infrastructure\controllers\UserController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Log;


class AuthLaravelController extends Controller
{
    public function register(Request $request){

        Log::debug("entra");

        $this->reCAPTCHA($request);

        $rules = [
            'name'=>'required|max:50',
            'lastName'=>'max:50',
            'email'=>'required|email|unique:users',
            'password' => 'required|confirmed'  
          ];
          
        $validator = Validator::make($request->all(), $rules);
        
        if (!$validator->passes()) {
            
            return response($validator->errors(),400);
        } 

        $passHash = Hash::make($request->password);

        try{
  
            ((new UserController())->store((string) Str::uuid(),
                                                $request->name,
                                                $request->last_name,
                                                $request->email,
                                                $passHash
                                              ));
            return response('',201);                                                          
    
          }catch(\Exception $e){
            
            return response(json_encode($e->toArray()),$e->errorHttp());
          }

    }

    public function login(Request $request){

        $rules = [
            'email'=>'required|email',
            'password' => 'required'
          ];

        $validator = Validator::make($request->user, $rules);
        
        if (!$validator->passes()) {
            return response('',400);
        } 

        if(!auth()->attempt($request->user)){
            return response (['msg'=>'Email o contraseña inválidas'],401);
        }
        

        $accessToken = $this->getToken($request->user['email']);
        return response(['access_token' => $accessToken],200);

    }

    public function emailVerificationNotice($id, Request $request){
      
     

      if (!$request->hasValidSignature()) {
        return response()->json(["msg" => "Invalid/Expired url provided."], 401);
      }
      
      $user = User::findOrFail($id);

      if (!$user->hasVerifiedEmail()) {
          $user->markEmailAsVerified();
      }


      return response('Ok',204);
    }
    
    /*public function changePassword(Request $request){

       # Validation
       $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|confirmed',
       ]);


      #Match The Old Password
      if(!Hash::check($request->old_password, auth()->user()->password)){
          return response('',401);
      }


      #Update the new Password
      User::whereId(auth()->user()->id)->update([
          'password' => Hash::make($request->new_password)
      ]);

      return response('',204);

    }

    

    public function resendVerification(){

      if (auth()->user()->hasVerifiedEmail()) {
        return response('',400);
      }

      auth()->user()->sendEmailVerificationNotification();

      return response('',200);
      
    }

    public function forgotPassEmail(Request $request){

      $this->reCAPTCHA($request);

      $rules = [
          'email'=>'required|email|exists:App\Models\User,email',
        ];
        
      $validator = Validator::make($request->all(), $rules);
      
      if (!$validator->passes()) {
          
          return response($validator->errors(),400);
      }
      
      $status = Password::sendResetLink(
        $request->only('email')
      );

      //Log::debug("status email send: ".$status);

      response('',200);
    }

    public function resetPassword(Request $request){

      $rules = [
        'token' => 'required',
        'email' => 'required|email|exists:App\Models\User,email',
        'password' => 'required|min:6|confirmed',
      ];

      $validator = Validator::make($request->all(), $rules);
      
      if (!$validator->passes()) {
          return response('',400);
      }
      
      $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
          
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
      );
      //Log::debug('status '.$status);
      if($status=='passwords.user')
        return response('',404);
      else
        if($status=='passwords.token')
          return response('',401);
        else
          if($status=='passwords.reset')
            return response('',204);
          else
            return response('',500);

    }
    */

    private function reCAPTCHA(Request $request){

      if(\Config::get('app.captcha_enable')){

        $key = \Config::get('app.captcha_key');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $key,
        'response' => $request->captcha,
        'remoteip'=>$request->ip()
        ]);

        $jsonData = $response->json();

        if(!$jsonData['success'])
          return response('',429);

      }

    }
   
    private function getToken(string $email){

      $user = User::where('email', $email)->first();

      //Log::debug("user: ".$user->name);
      $user->tokens()->delete();
      $accessToken = $user->createToken('authToken')->plainTextToken;

      return $accessToken;
    }
}
