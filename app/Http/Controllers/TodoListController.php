<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TodoListController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }
        if ((int)request()->query('archive') == 1) {
            $todolist = TodoList::where(['is_archived' => 1, 'admin_id' => $admin->id])->get();
        } else {
            $todolist = TodoList::where(['is_archived' => 0, 'admin_id' => $admin->id])
                ->orderBy('is_completed', 'asc')
                ->orderBy('id', 'desc')
                ->get();
        }
        if ($todolist->isEmpty()) {
            throw new Exception('Not Found', 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfully',
            'data' => $todolist->toArray(),
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();
            if (! $admin) {
                throw new Exception('Unathenticated', 401);
            }
            $validated = $request->validate([
                'task' => 'required',
                'description' => 'nullable',
                'due_date' => 'nullable|date',
            ]);
            $todo = TodoList::create([
                'task' => $validated['task'],
                'description' => $validated['description'] ?? '',
                'due_date' => $validated['due_date'] ?? null,
                'admin_id' => $admin->id,
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Created succsfully',
                'data' => $todo,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function updateCompleted($id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! $admin) {
                throw new Exception('Unathenticated', 401);
            }
            $todoItem = TodoList::where(['id' => $id])->first();
            $todoItem->update([
                'is_completed' => $todoItem->is_completed ? 0 : 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Updated Completed successfully',
                'data' => $todoItem,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'task' => ['sometimes', 'string'],
            'description' => ['sometimes', 'nullable'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ]);
        $auth = Auth::guard('admin')->user();
        if (! $auth) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unathorized',
            ], 401);
        }
        $todoItem = TodoList::where([
            'id' => $id,
            'admin_id' => $auth->id,
        ])->first();
        if (! $todoItem) {

            return response()->json([
                'status' => 'error',
                'message' => 'Not found',
            ], 404);
        }
        $todoItem->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully',
            'data' => $todoItem,
        ]);
    }

    public function delete($id)
    {
        try {
            $todoItem = TodoList::where(['id' => $id])->first();
            if (! $todoItem) {
                throw new Exception('Not found', 404);
            }
            $todoItem->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Successfully',
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function archive($id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! $admin) {
                throw new Exception('Unathenticated', 401);
            }
            $todoItem = TodoList::where(['id' => $id, 'admin_id' => $admin->id])->first();
            if (! $todoItem) {
                throw new Exception('Not found', 404);
            }
            $todoItem->update([
                'is_archived' => $todoItem->is_archived ? 0 : 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Archived successfully',
                'data' => $todoItem,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function export()
    {
        $filename = 'todoList' . Carbon::now()->format('YmdHis');
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'id',
                'task',
                'description',
                'is_completed',
                'due_date',
                'admin_id',
                'created_at',
            ]);

            DB::table('todo_lists')
                ->orderByDesc('id')
                ->chunk(100, function ($items) use ($handle) {
                    foreach ($items as $item) {
                        fputcsv($handle, [
                            $item->id ?? '',
                            $item->task ?? '',
                            $item->description ?? '',
                            $item->is_completed ?? '',
                            $item->due_date ?? '',
                            $item->admin_id ?? '',
                            $item->created_at ?? '',
                        ]);
                    }
                });
            fclose($handle);
        }, 200, $headers);
    }
}
