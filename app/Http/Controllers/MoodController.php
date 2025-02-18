<?php

namespace App\Http\Controllers;

use App\Enums\Moods;
use App\Models\Mood;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class MoodController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood' => ['required', new Enum(Moods::class)],
            'note' => 'max:200',
        ]);

        $moods = Mood::create([
            'mood' => $validated['mood'],
            'note' => $validated['note'] ?? '',
            'admin_id' => Auth::guard('admin')->user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mood created successfully',
            'data' => $moods
        ], 201);
    }

    public function latest(): JsonResponse
    {
        try {
            $adminId = Auth::guard('admin')->user()->id;
            if (!$adminId) {
                throw new Exception('Unauthenticated', 401);
            }
            $latest = Mood::where(['admin_id' => $adminId])->orderBy('id', 'desc')->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Fetched Successfully',
                'data' => $latest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
