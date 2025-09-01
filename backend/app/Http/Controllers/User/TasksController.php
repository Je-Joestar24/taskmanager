<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;

class TasksController extends Controller
{
    /**
     * Display a listing of the authenticated user's tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Build query with scopes and search
        $query = $user->tasks();
        
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'order');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if ($sortBy === 'order') {
            $query->orderBy('order', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Cache the results for 5 minutes to improve performance
        $cacheKey = "user_tasks_{$user->id}_" . md5($request->fullUrl());
        $tasks = Cache::remember($cacheKey, 300, function () use ($query) {
            return $query->get();
        });

        return response()->json([
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'total' => $tasks->count(),
                'pending' => $tasks->where('status', 'pending')->count(),
                'completed' => $tasks->where('status', 'completed')->count(),
                'low_priority' => $tasks->where('priority', 'low')->count(),
                'medium_priority' => $tasks->where('priority', 'medium')->count(),
                'high_priority' => $tasks->where('priority', 'high')->count(),
            ]
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:pending,completed',
            'priority' => 'sometimes|in:low,medium,high',
        ]);

        $user = $request->user();
        
        // Get the highest order number and increment by 1
        $maxOrder = $user->tasks()->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;
        $validated['user_id'] = $user->id;

        $task = Task::create($validated);

        // Clear cache for this user
        $this->clearUserTasksCache($user->id);

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task)
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        // Ensure user can only access their own tasks
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'data' => new TaskResource($task)
        ]);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        // Ensure user can only update their own tasks
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'status' => 'sometimes|in:pending,completed',
            'priority' => 'sometimes|in:low,medium,high',
        ]);

        $task->update($validated);

        // Clear cache for this user
        $this->clearUserTasksCache($task->user_id);

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task)
        ]);
    }

    /**
     * Reorder tasks for the authenticated user.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|integer|min:0',
        ]);

        $user = $request->user();
        
        // Verify all tasks belong to the user
        $taskIds = collect($validated['tasks'])->pluck('id');
        $userTaskIds = $user->tasks()->pluck('id');
        
        if (!$taskIds->every(fn($id) => $userTaskIds->contains($id))) {
            return response()->json(['message' => 'Unauthorized task access.'], 403);
        }

        DB::transaction(function () use ($validated) {
            foreach ($validated['tasks'] as $taskData) {
                Task::where('id', $taskData['id'])
                    ->update(['order' => $taskData['order']]);
            }
        });

        // Clear cache for this user
        $this->clearUserTasksCache($user->id);

        return response()->json([
            'message' => 'Tasks reordered successfully.'
        ]);
    }

    public function toggleStatus(Request $request, Task $task): JsonResponse
    {
        // Ensure user can only toggle their own tasks
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $task->update([
            'status' => $task->status === 'pending' ? 'completed' : 'pending'
        ]);

        // Clear cache for this user
        $this->clearUserTasksCache($task->user_id);

        return response()->json([
            'message' => 'Task status updated successfully.',
            'data' => new TaskResource($task)
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $cacheKey = "user_task_stats_{$user->id}";
        $stats = Cache::remember($cacheKey, 300, function () use ($user) {
            // Get all tasks for the user
            $allTasks = $user->tasks()->get();
            
            return [
                'total' => $allTasks->count(),
                'pending' => $allTasks->where('status', 'pending')->count(),
                'completed' => $allTasks->where('status', 'completed')->count(),
                'low_priority' => $allTasks->where('priority', 'low')->count(),
                'medium_priority' => $allTasks->where('priority', 'medium')->count(),
                'high_priority' => $allTasks->where('priority', 'high')->count(),
                'completion_rate' => $allTasks->count() > 0 
                    ? round(($allTasks->where('status', 'completed')->count() / $allTasks->count()) * 100, 2)
                    : 0,
            ];
        });

        return response()->json([
            'data' => $stats
        ]);
    }

    private function clearUserTasksCache(int $userId): void
    {
        Cache::forget("user_task_stats_{$userId}");
    }
}
