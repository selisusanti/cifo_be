<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use App\Services\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Paginator;

class UserController extends Controller
{
    public function index(Request $request){
        $listUser       = User::latest()->get();
        $page           = $request->page ? $request->page : 1 ;
        $perPage        = $request->limit ?? 10;
        $ALL_listUser   = collect($listUser);
        $dispatcher_new = new Paginator($ALL_listUser->forPage($page, $perPage), $ALL_listUser->count(), $perPage, $page, [
            'path' => url("api/user")
        ]); 
        return Response::success($dispatcher_new);
    }

    public function userProfile(){
        $user = User::where('id',Auth::user()->id)->first();
        return Response::success($user);
    }


}
