<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be minimum of 6 characters.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $token = "dsajds";

            return response()->json(['message' => 'Login successful', 'token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
