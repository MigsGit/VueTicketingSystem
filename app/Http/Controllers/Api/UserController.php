<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getUserInfo(){
        // return 'true';
        return $user = User::whereNull('deleted_at')->get();

    }
}
