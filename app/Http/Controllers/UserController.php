<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class UserController extends Controller
{
    public function index(){
        $listUser                   = User::get();
        $response['status']         = true;
        $response['message']        = 'Berhasil registrasi';
        $response['data']           = $listUser;
        return response()->json($response, 200);
    }

}
