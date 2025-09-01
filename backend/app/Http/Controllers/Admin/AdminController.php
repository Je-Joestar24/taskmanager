<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with overview statistics.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $cacheKey = 'admin_dashboard_stats';
        $stats = Cache::remember($cacheKey, 300, function () {
            $totalUsers = User::count();
            $totalTasks = Task::count();
            $pendingTasks = Task::where('status', 'pending')->count();
            $completedTasks = Task::where('status', 'completed')->count();
            
            return [
                'total_users' => $totalUsers,
                'total_tasks' => $totalTasks,
                'pending_tasks' => $pendingTasks,
                'completed_tasks' => $completedTasks,
                'completion_rate' => $totalTasks > 0 
                    ? round(($completedTasks / $totalTasks) * 100, 2) 
                    : 0,
            ];
        });

        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Get all users with their task statistics.
     */
    public function users(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        
        $users = User::withCount(['tasks as total_tasks', 'tasks as pending_tasks' => function ($query) {
            $query->where('status', 'pending');
        }, 'tasks as completed_tasks' => function ($query) {
            $query->where('status', 'completed');
        }])
        ->with(['tasks' => function ($query) {
            $query->latest()->take(5); // Get latest 5 tasks per user
        }])
        ->paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Get all tasks across all users with pagination.
     */
    public function allTasks(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');
        $priority = $request->get('priority');
        $search = $request->get('search');

        $query = Task::with('user:id,name,email,role');

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $tasks->items(),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ]
        ]);
    }

    /**
     * Get user-specific task statistics.
     */
    public function userStats(Request $request, User $user): JsonResponse
    {
        $cacheKey = "admin_user_stats_{$user->id}";
        $stats = Cache::remember($cacheKey, 300, function () use ($user) {
            // Get all tasks for the user first, then use collection methods
            $allTasks = $user->tasks()->get();
            
            return [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'total_tasks' => $allTasks->count(),
                'pending_tasks' => $allTasks->where('status', 'pending')->count(),
                'completed_tasks' => $allTasks->where('status', 'completed')->count(),
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
}
