<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ToggleLockUserFromAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Validate request data
        // Get user data from request and model
        // apply condition for according to whats in the database
        // then update it
        $validated = $request->validate([
            'user_id' => 'required|int',
        ]);
        try {
            $user = User::where('id', $validated['user_id'])->first();
            $user->update([
                'lock' => $user->lock ? 0 : 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $user->lock ? 'Locked' : 'Unlocked',
            'data' => $user,
        ]);
    }
}
