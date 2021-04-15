<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User; 

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
     
   
        $this->validate($request, [ 
            'email' => 'required|email|min:4|max:20',
            'password' => 'required|min:8',
        ]); 
        $six_digit_random_number = mt_rand(100000, 999999);
        $user = User::create([ 
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'registered_at' => date('Y-m-d H:i:s'),
            'digit' => $six_digit_random_number
        ]);
 
        sendMail([
            'file'=>'confirm',
            'subject'=>'Confirm',
            'data'=>['pin'=>$six_digit_random_number],
            'email'=>$request->email
        ]);
      // $token = $user->createToken('LaravelTestAuthApp')->accessToken;
 
        return response()->json(['success' => 'Account created successfully','message'=>'Please check you mail you must recieve Digit'], 200);
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'varified' => 'yes'
        ];
 
        if (auth()->attempt($data)) {
            if(auth()->user()->varified=='yes'){
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token,'user'=>auth()->user()], 200);
            }else{
            return response()->json(['error' => 'Account not Varified'], 401);

            }
        } else {
            return response()->json(['error' => 'Invalid Creds'], 401);
        }
    }
    /**
     * ValidateAccount
     */
    public function validateAccount(Request $request)
    {
        $this->validate($request, [ 
            'email' => 'required|email',
            'digit' => 'required|min:6',
        ]); 

        $user = User::where(['email'=>$request->email,'digit'=>$request->digit])->first();
        if($user){
            User::where('id',$user->id)->update(['varified'=>'yes']);
            $token = $user->createToken('LaravelTestAuthApp')->accessToken;
            return response()->json(['success' => 'Account Varified','token'=>$token], 200);

        }else{
            return response()->json(['error' => 'Invalid Values'], 401);

        }

    }
}
