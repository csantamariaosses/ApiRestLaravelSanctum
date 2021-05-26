<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Auth;


class AuthController extends Controller
{
    //
    public function register(Request $request) {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create( [
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password'])
        ]);


        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login( Request $request ) {
        $email = $request['email'];
        $password = $request['password'];
        if( !Auth::attempt( array('email' => $email, 'password' => $password) )) {
            return response()->json([
                'message' => 'Invalid Login'              
            ] ,401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token =  $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function infouser(Request $request) {
        return $request->user();
    }
}
