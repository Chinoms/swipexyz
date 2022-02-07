<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateUser = $request->validate([
            'name' => 'required|max:55',
            'surname' => 'required|max:55',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'username' => 'required|unique:users|max:20'
        ]);

        //Hash the password before proceeding to store in DB
        $validateUser['password'] = Hash::make($request->password);

        $user = User::create($validateUser);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['message' => 'Account created', 'accessToken' => $accessToken], 200);
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $loginInfo = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($loginInfo)) {
            $userInfo = User::where('email', $request->email)->first();
            //return response()->json($userInfo);
            $accessToken = $userInfo->createToken('authToken')->accessToken;
            return response()->json(['accessToken' => $accessToken, 'userInfo' => $userInfo], 200);
        } else {
            return response()->json(['error' => 'Invalid login details'], 400);
        }
    }
}
