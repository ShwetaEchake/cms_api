<?php

namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
   public function login(Request $request)
       {
           $credentials = $request->only('email', 'password');
           $validator = Validator::make($credentials, [
               'email' => 'required|email',
               'password' => 'required',
           ]);
           if ($validator->fails()) {
               return response()->json($validator->errors(), 422);
           }
           if (auth()->attempt($credentials)) {
               $user = auth()->user();
               return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
           }
           return response()->json(['error' => 'Unauthorized'], 401);
       }

       public function logout()
       {
           auth()->user()->tokens()->delete();
           return response()->json(['message' => 'Logged out successfully']);
       }
}
