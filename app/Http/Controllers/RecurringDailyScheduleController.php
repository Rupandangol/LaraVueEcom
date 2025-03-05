<?php

namespace App\Http\Controllers;

use App\Enums\DailyScheduleStatus;
use App\Models\DailySchedule;
use App\Models\RecurringDailySchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringDailyScheduleController extends Controller
{
    public function index()
    {
        try {
            $rDailySchedule = RecurringDailySchedule::select('id', 'title', 'description')->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Fetched recurring task successfully',
                'data' => $rDailySchedule->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeInDailySchedule(Request $request)
    {
        $validated = $request->validate([
            'recurring_id' => 'required',
            'date'=>'required'
        ]);
        try {
            $recurringDS = RecurringDailySchedule::where('id', $validated['recurring_id'])->first();
            $overlap = DailySchedule::where('admin_id', Auth::guard('admin')->user()->id)
                ->whereDate('date', Carbon::parse($validated['date'])->format('Y-m-d'))
                ->where(function ($query) use ($recurringDS) {
                    $query->whereBetween('start_time', [$recurringDS['start_time'], $recurringDS['end_time']])
                        ->orWhereBetween('end_time', [$recurringDS['start_time'], $recurringDS['end_time']])
                        ->orWhere(function ($query) use ($recurringDS) {
                            $query->where('start_time', '<=', $recurringDS['start_time'])
                                ->where('end_time', '>=', $recurringDS['end_time']);
                        });
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The specified time range overlaps with an existing schedule.'
                ], 401);
            }
            DailySchedule::create([
                'title' => $recurringDS['title'],
                'description' => $recurringDS['description'],
                'start_time' => $recurringDS['start_time'],
                'end_time' => $recurringDS['end_time'],
                'location' => $recurringDS['location'],
                'status' => $recurringDS['status'],
                'date' => Carbon::parse($validated['date'])->format('Y-m-d'),
                'admin_id' => Auth::guard('admin')->user()->id
            ]);
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required'
        ]);
        try {
            $validated['status'] = DailyScheduleStatus::PENDING;
            $validated['date'] = Carbon::now()->format('Y-m-d');
            RecurringDailySchedule::create($validated);
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
    public function delete($id)
    {
        try {
            $recurringDS = RecurringDailySchedule::where(['id' => $id])->first();
            if (!$recurringDS) {
                throw new Exception('Not found', 404);
            }
            $recurringDS->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
