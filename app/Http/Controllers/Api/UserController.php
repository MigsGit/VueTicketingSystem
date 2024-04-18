<?php

namespace App\Http\Controllers\Api;


use DataTables;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserInfoRequest;

class UserController extends Controller
{
    public function getUserInfo()
    {
        // return 'true';
        $user = User::whereNull('deleted_at')->get();

        return DataTables::of($user)
        ->make(true);
    }
    public function readUserInfo(Request $request)
    {
        try {
            $user =User::findOrFail($request->data_id);
            return response($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function saveUserInfo(UserInfoRequest $request)
    {
        try {
            if(isset($request->data_id)){

                // return $request->all();
                User::where('id',$request->data_id)->update([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    // 'password' => Hash::make('pmi12345'),
                ]);
                return response(["result"=>"success"]);
            }else{
                // return 'false';
                User::insert([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'password' => Hash::make('pmi12345'),
                    'created_at' => date('Y-m-d H:m:s'),
                ]);
            }
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Internal error, please try again later.',
                'Exception Error:' => $err
            ], 400);
        }
    }
    public function getUserOption (Request $request){
        try {
            $user = User::whereNull('deleted_at')->get(['id','name']);
            return response()->json([ 'arr_user' => $user]);
        } catch (\Throwable $th) {
            throw $th;
        }
        // $arr_user  = [];
        // foreach($user as $key => $val_user){
        //     $arr_user['value'][] 		= $val_user['id'];
        //     $arr_user['label'][] 		= $val_user['name'];
        // }
    }
}
