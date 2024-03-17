<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(LoginRequest $request){
        $fields = $request->validated();
        $user_info = User::where('email', $request->email)->first();

        if(isset($user_info)){
            if($user_info['deleted_at'] != null){
                return response()->json([
                    'msg' => "User account is deactivated <br> Please call administrator."
                ], 402);
            }
            if(!Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])){
                return response()->json([
                    'msg' => "Username or Password is incorrect"
                ], 401);
            }
            $request->session()->put('id', Auth::user()->id);
            $request->session()->put('username', Auth::user()->email);

            return response()->json(['msg' => 'Login Successful','userData' => Auth::user()]);

        }
        else{
            return response()->json(['result' => 0, 'msg' => 'User Not Registered!'], 404);
        }
    }
    public function logout(Request $request){
        $var = $request->session()->forget(['id','username']);
        // $var = $request->session()->flush();
    }
    public function check_ses(Request $request){
        $var = $request->session()->get('id');
        $var1 = $request->session()->get('username');
        $var2 = $request->session()->token();
        return response()->json(['session_id' => $var,'session_userN' => $var1, 'token' => $var2]);
    }
}
