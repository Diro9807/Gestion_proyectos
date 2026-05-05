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
            'project_task_id' => $request->project_task_id,
            'user_id' => auth()->id()
        ]);
    }

    // 🔹 Actualizar
    public function update(Request $request, Task $task)
    {
        $task->update([
            'name' => $request->name
        ]);

        return $task;
    }

    // 🔹 Eliminar
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
