<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date_format:Y-m-d',
        ]);

        $user = $request->user();

        // Set high priority for admin-created tasks
        if ($user->role === 'admin') {
            $validated['priority'] = 'high';
        }

        $validated['created_by'] = $user->id;
        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], 201);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    // Laravel will automatically throw AuthorizationException
    $this->authorize('update', $task);

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'due_date' => 'sometimes|nullable|date_format:Y-m-d',
    ]);

    $task->update($validated);

    return response()->json([
        'message' => 'Task updated successfully',
        'task' => $task,
    ]);
}

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
       $user = $request->user();

        // Only admins can delete admin-created tasks
        if ($task->created_by_role === 'admin' && $user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized: Only admins can delete admin tasks',
            ], 403);
        }

        // Only team leaders and admins can delete tasks
        if (!in_array($user->role, ['admin', 'lead'])) {
            return response()->json([
                'message' => 'Unauthorized: Only team leaders and admins can delete tasks',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}
