<?php

namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Facades\Validator;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $accessToken = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'response_code' => 200,
                    'status'        => 'success',
                    'message'       => 'Login successful',
                    'user_info'     => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                    ],
                    'token' => $accessToken,
                ]);
            }

            return response()->json([
                'response_code' => 401,
                'status'        => 'error',
                'message'       => 'Unauthorized',
            ], 401);

        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Login failed',
            ], 500);
        }
    }

       public function logout()
       {
            try {
                if (Auth::check()) {
                    Auth::user()->tokens()->delete();

                    return response()->json([
                        'response_code' => 200,
                        'status'        => 'success',
                        'message'       => 'Successfully logged out',
                    ]);
                }

                return response()->json([
                    'response_code' => 401,
                    'status'        => 'error',
                    'message'       => 'User not authenticated',
                ], 401);
            } catch (\Exception $e) {
                Log::error('Logout Error: ' . $e->getMessage());

                return response()->json([
                    'response_code' => 500,
                    'status'        => 'error',
                    'message'       => 'An error occurred during logout',
                ], 500);
            }
       }
}
