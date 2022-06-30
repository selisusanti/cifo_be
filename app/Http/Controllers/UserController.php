<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use App\Services\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $listUser                   = User::get();
        $response['status']         = true;
        $response['message']        = 'Berhasil registrasi';
        $response['data']           = $listUser;
        return response()->json($response, 200);
    }

    public function userProfile(){
        $user = User::where('id',Auth::user()->id)->first();
        return Response::success($user);
    }


}
