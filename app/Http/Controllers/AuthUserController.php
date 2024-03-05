<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends Controller
{
    public function registerstore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'=> 'required|string|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, \Illuminate\Http\Response::HTTP_CREATED);
    }

    public function loginstore(Request $request)
    {
        $credentials = $request->validate([
            'email'=> 'required|string|exists:users',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            return \response()->json($request->user(), \Illuminate\Http\Response::HTTP_CREATED);
        }

        return \response([
            'email' => 'The provided credentials don not match with our records'
        ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
