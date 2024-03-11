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

    public function userInfoTwo()
    {
        //to check if the user is authenticated

        if (!auth()->check())
        {
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        //fetch the user information
        $user = auth()->user();

        //include additional user data when required

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email'=> $user->email,
        ];

            //Adding more fields as required

            //the response structure is provided by

            $response = [
                'succes'=> true,
                'user' => $userData,

            ];

            //This returns a JSON response

            return response()->json($response, 200);

    }
}
