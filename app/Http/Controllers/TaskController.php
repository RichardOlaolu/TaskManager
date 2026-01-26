<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Enums\Role;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], 201);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        // Policy will automatically check view permission via middleware
        $this->authorize('view', $task);

        return response()->json($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $validated = $request->validated();

        // Policy will automatically check update permission
        $this->authorize('update', $task);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Policy will automatically check delete permission
        $this->authorize('delete', $task);

        // Additional business logic from your original controller
        $user = $request->user();

        // Note: This logic should be moved to the TaskPolicy::delete method
        // Only admins can delete admin-created tasks
        if ($task->created_by_role === Role::Admin && $user->role !== Role::Admin) {
            return response()->json([
                'message' => 'Unauthorized: Only admins can delete admin tasks',
            ], 403);
        }

        // Only team leaders and admins can delete tasks
        if (!in_array($user->role, [Role::Admin, Role::Lead])) {
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
