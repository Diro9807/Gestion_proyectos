<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // 🔹 Obtener tareas por proyecto
    public function index($projectId)
    {
        return Task::where('project_task_id', $projectId)->get();
    }

    // 🔹 Crear tarea
    public function store(Request $request)
    {
        return Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'project_task_id' => $request->project_task_id,
            'user_id' => 1
        ]);
    }

    // 🔹 Actualizar
    public function update(Request $request, Task $task)
    {
        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        return response()->json($task);
    }

    // 🔹 Eliminar
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
