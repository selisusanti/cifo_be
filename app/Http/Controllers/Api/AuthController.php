<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Str;
use App\Services\Validate;
use App\Services\Response;
use DB;
use App\Exceptions\ApplicationException;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user               = Auth::user();
            $success['token']   = 'Bearer ' . $user->createToken('myinventory')->accessToken;
            $success['user']    =  $user;
            return Response::success($success);
        }
        else{
            throw new ApplicationException("errors.unauthorized");
        }
    }

    public function register(Request $request)
    {   
        $validate = Validator::make($request->all(),[
            'name' => ['string', 'required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'min:7']
        ]);

        if($validate->fails()){
            // $response['status'] = false;
            // $response['message'] = 'Gagal registrasi';
            // $response['error'] = $validate->errors();
            // return response()->json($response, 422);
            return Response::errorValidate($validate->errors());
        }

        
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
        ]);

        $response['user']   = $user;
        $response['token']  = 'Bearer ' . $user->createToken('myinventory')->accessToken;
        return Response::success($response);
        
    }


    public function resetPassword(Request $request){

        $validate = Validator::make($request->all(),[
            'password'                            => 'required|min:8|max:12|same:password_confirmation|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).+$/',
            'password_confirmation'               => 'required|min:8|max:12',
        ]);

        if($validate->fails()){
            $response['error']    = $validate->errors();
            return Response::errorValidate($response);
        }

        try {

            DB::beginTransaction();

            $user = User::where('id',Auth::user()->id)->first();
            $user->update([
                'password'      => Hash::make($request['password'])
            ]);
    
            DB::commit();
            return Response::success($user);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new ApplicationException("error.reset_password",['id' => $id]);
        }
        
    }
}
