<?php

namespace App\Http\Controllers;

use App\Enums\DailyScheduleStatus;
use App\Models\DailySchedule;
use Carbon\Carbon;
use Database\Factories\DailyScheduleFactory;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function dailyScheduleAnalytics(Request $request)
    {
        $query = DailySchedule::query();
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->filled('start_time')) {
            $query->where('start_time', '>=', $request->start_time);
        }
        if ($request->filled('end_time')) {
            $query->where('end_time', '<=', $request->end_time);
        }
        if ($request->filled('is_all_day')) {
            $query->where('is_all_day', $request->is_all_day);
        }
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        $total_count = (clone $query)->count();
        $title_group = $this->getTotalCount((clone $query), 'title', 'desc', 10);
        $date_count = $this->getTotalCount((clone $query), 'date', 'desc', 3);
        $status_count = $this->getTotalCount((clone $query), 'status', 'desc');
        $is_all_day_count = $this->getTotalCount((clone $query), 'is_all_day', 'desc');
        $location_count = $this->getTotalCount((clone $query), 'location', 'desc',5);
        $time_count = $this->getTotalCount((clone $query), 'start_time', 'desc',5);
        return response()->json([
            'status' => 'success',
            'message' => 'fetch successfully',
            'data' => [
                'total_count' => $total_count,
                'is_all_day_count' => $is_all_day_count,
                'status_count' => $status_count,
                'title_group' => $title_group,
                'date_count' => $date_count,
                'location_count' => $location_count,
                'time_count' => $time_count,
                'dailySchedule' => $query->latest()->paginate(10)
            ]
        ]);
    }

    protected function getTotalCount($query, $field, $orderBy, $limit = null)
    {
        $total_count = $query->select($field, DB::raw('count(*) as total'))
            ->groupBy($field)
            ->orderBy('total', $orderBy)
            ->limit($limit)
            ->get();
        return $total_count;
    }
}
