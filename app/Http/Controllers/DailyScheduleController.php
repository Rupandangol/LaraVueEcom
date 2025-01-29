<?php

namespace App\Http\Controllers;

use App\Enums\DailyScheduleStatus;
use App\Models\DailySchedule;
use Carbon\Carbon;
use Database\Factories\DailyScheduleFactory;
use Illuminate\Http\Request;

class DailyScheduleController extends Controller
{
    public function store(Request $request)
    {
        /**
         * get validated data
         * start time and end time has to be unique for the same day
         * save validated data with auth admin_id
         */
        $admin = auth()->guard('admin')->user();
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
        ]);
        $validated['status'] = DailyScheduleStatus::PENDING;
        $validated['admin_id'] = $admin->id;
        try {
            $overlap = DailySchedule::whereDate('date', Carbon::parse(now())->format('Y-m-d'))
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The specified time range overlaps with an existing schedule.'
                ], 401);
            }
            DailySchedule::create($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Daily schedule updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function index()
    {
        try {
            $dailySchedule = DailySchedule::wheredate('date', Carbon::now()->format('Y-m-d'))->orderBy('start_time', 'asc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'fetched successfully',
                'data' => $dailySchedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
