<?php

namespace App\Http\Controllers;

use App\Enums\Moods;
use App\Models\Mood;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function moodAnalytics(Request $request)
    {
        $query = DB::table('moods');
        if ($request->filled('mood')) {
            $query->where('mood', $request->get('mood'));
        }
        if ($request->filled('admin')) {
            $query->where('admin_id', $request->get('admin'));
        }
        if ($request->filled('note')) {
            $query->where('note', 'like', '%' . $request->get('note') . '%');
        }
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->get('from_date'));
        }
        $moods_data = $query->paginate(10);
        $total_count = (clone $query)->count();

        $top_moods = (clone $query)
            ->select('mood', DB::raw('COUNT(*) as total'))
            ->groupBy('mood')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get()
            ->toArray();
        $top_notes = (clone $query)
            ->select('note', DB::raw('COUNT(*) as total'))
            ->groupBy('note')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get()
            ->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetch Successful',
            'data' => [
                'total_count' => $total_count,
                'top_moods' => $top_moods,
                'top_notes' => $top_notes,
                'moods_data' => $moods_data,
            ]
        ]);
    }
}
