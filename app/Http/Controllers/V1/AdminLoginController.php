<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin || Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Provided credential is incorrect']
            ]);
        }

        return response()->json([
            'admin' => $admin,
            'token' => $admin->createToken($request->email, ['role:admin'])->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {

        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout Successfully'
        ], 200);
    }
}
