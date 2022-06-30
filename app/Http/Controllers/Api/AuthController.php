<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    //
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token']  = 'Bearer ' . $user->createToken('myinventory')->accessToken;
            $success['user']  =  $user;
            return response()->json(['success' => $success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {   
        $validate = Validator::make($request->all(),[
            'name' => ['string', 'required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if($validate->fails()){
            $response['status'] = false;
            $response['message'] = 'Gagal registrasi';
            $response['error'] = $validate->errors();
            
            return response()->json($response, 422);
        }
        
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $response['status']         = true;
        $response['message']        = 'Berhasil registrasi';
        $response['data']['user']    = $user;
        $response['data']['token']  = 'Bearer ' . $user->createToken('myinventory')->accessToken;

        return response()->json($response, 200);
        
    }
}
