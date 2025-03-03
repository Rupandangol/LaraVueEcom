<?php

namespace App\Http\Controllers;

use App\Enums\DailyScheduleStatus;
use App\Models\DailySchedule;
use Carbon\Carbon;
use Database\Factories\DailyScheduleFactory;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

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
            'date' => 'required'
        ]);
        $validated['status'] = DailyScheduleStatus::PENDING;
        $validated['date'] = Carbon::parse($validated['date'])->format('Y-m-d');
        $validated['admin_id'] = $admin->id;
        try {
            $overlap = DailySchedule::where('admin_id', $admin->id)
                ->whereDate('date', Carbon::parse($validated['date'])->format('Y-m-d'))
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

    public function index(string $date = null)
    {
        try {
            $getdate = ($date != null) ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $dailySchedule = DailySchedule::wheredate('date', $getdate)->orderBy('start_time', 'asc')->get();
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

    public function updateStatus($id, Request $request)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                new Enum(DailyScheduleStatus::class)
            ]
        ]);
        try {
            $dailySchedule = DailySchedule::where(['id' => $id])->first();
            if (!$dailySchedule) {
                throw new Exception('No Schedule Found', 404);
            }
            $dailySchedule->update([
                'status' => $validated['status']
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'title' => ['sometimes'],
            'description' => ['sometimes'],
            'start_time' => ['sometimes'],
            'end_time' => ['sometimes'],
            'location' => ['sometimes'],
        ]);
        try {
            $dailySchedule = DailySchedule::where(['id' => $id])->first();
            if (!$dailySchedule) {
                throw new Exception('No daily schedule found', 404);
            }
            $dailySchedule::update($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Updated Successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getTasksMonths($date = null)
    {
        try {
            $date = explode('-', $date);
            $task = DailySchedule::whereMonth('date', $date[1])->whereYear('date', $date[0])->get(['date', 'title']);
            return response()->json([
                'status' => 'success',
                'message' => 'Fetched successfullly',
                'data' => $task->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function deleteDailyTask($id)
    {
        try {
            $dailyTask = DailySchedule::where(['id' => $id])->first();
            if (!$dailyTask) {
                throw new Exception('Not found', 404);
            }
            $dailyTask->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
