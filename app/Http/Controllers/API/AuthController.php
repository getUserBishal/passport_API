<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('Token')->accessToken;
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::query()->where('email', $data['email'])->firstOrFail();

        if (Hash::check($data['password'], $user->password)) {

            $token = $user->createToken('Token')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'unauthorized'], 401);
    }

    public function userInfo()
    {

        $user = auth()->user();
        return response()->json(['user'=> $user],200);
    }
}
