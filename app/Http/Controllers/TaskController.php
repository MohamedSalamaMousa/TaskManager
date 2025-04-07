<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:pending,in-progress,completed',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }


    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,in-progress,completed',
        ]);

        $task->update($validatedData);

        return response()->json($task, 200);
    }


    public function destroy(Task $task)
    {
        // Ensure the authenticated user is the owner of the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }
}
