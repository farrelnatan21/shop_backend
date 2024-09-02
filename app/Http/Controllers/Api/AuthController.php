<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function registerSeller(Request $request)
  {
    $request->validate([
        'name' =>'required|string',
        'email' =>'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' =>'required|string|unique:users',
        'address' =>'required|string',
        'country' => 'required|string',
        'province' => 'required|string',
        'city' => 'required|string',
        'district' => 'required|string',
        'postal_code' => 'required|stiing',
        //validate photo
        'photo' =>'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $photo = null;
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo')->store('assets/users','public');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'address' => $request->address,
        'country' => $request->country,
        'province' => $request->province,
        'city' => $request->city,
        'district' => $request->district,
        'postal_code' => $request->postal_code,
        'photo' => $photo,
    ]);

    return response()->json([
       'status' =>'success',
       'message' => 'User registered',
        'data' => $user
        ], 201);

  }

  public function login(Request $request)
  {
    $request->validate([
        'email' =>'required|string|email',
        'password' => 'required|string',
    ]
    );

    $user = User::where('email', $request->email)->first();
    if (!$user ||!Hash::check($request->password, $user->password)) {
        return response()->json([
           'status' => 'error',
           'message' => 'Invalid credentials',
        ], 401);
    }

    $token = $user->createToken('authToken')->plainTextToken->delete();

    return response()->json([
       'status' =>'success',
       'message' => 'User logged in',
        'data' => [
            'user' => $user,
            'token' => $token
        ]
        ], 200);
  }

  public function logout(Request $request)
  {
    $request->user()->token()->revoke();

    return response()->json([
       'status' =>'success',
       'message' => 'User logged out'
        ], 200);
  }

  public function registerBuyer(Request $request)
  {
    $request->validate([
        'name' =>'required|string',
        'email' =>'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        ]);

        return response()->json([
           'status' =>'success',
           'message' => 'User registered',
            'data' => $user
            ], 201);
  }

  public function updateFcmToken(Request $request)
  {
    $request->validate([
        'fcm_token' =>'required|string',
    ]);

    $user = $request->user();
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json([
       'status' =>'success',
       'message' => 'FCM token updated',
        'data' => $user
        ], 200);
  }
}
