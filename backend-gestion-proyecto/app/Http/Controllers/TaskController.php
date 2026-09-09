<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    // Comprobación del usuario en el proyecto
    private function userBelongsToProject(Project $project)
    {
        $user = Auth::user();

        return $project->users()
            ->where('users.id_user', $user->id_user)
            ->exists();
    }

    // Comprobación de que un usuario pertenece al proyecto
    private function userBelongsToProjectById(Project $project, $userId)
    {
        return $project->users()
            ->where('users.id_user', $userId)
            ->exists();
    }

    // Obtener tareas por proyecto
    public function index(Project $project)
    {
        if (!$this->userBelongsToProject($project)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return $project->tasks()
            ->orderBy('position')
            ->get();
    }

    // Crear tarea
    public function store(Request $request)
    {
        $project = Project::find($request->project_task_id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        if (!$this->userBelongsToProject($project)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'user_id' => 'nullable|exists:users,id_user',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'due_date' => 'nullable|date|after_or_equal:end_date'
        ]);

        if (
            $request->user_id &&
            !$this->userBelongsToProjectById($project, $request->user_id)
        ) {
            return response()->json([
                'message' => 'El usuario asignado no pertenece al proyecto'
            ], 422);
        }

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'project_task_id' => $project->id_project,
            'user_id' => $request->user_id,
        ]);

        return response()->json($task, 201);
    }

    // Actualizar tarea
    public function update(Request $request, Task $task)
    {
        $project = Project::find($task->project_task_id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        if (!$this->userBelongsToProject($project)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'user_id' => 'nullable|exists:users,id_user',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'due_date' => 'nullable|date|after_or_equal:end_date'
        ]);

        if (
            $request->user_id &&
            !$this->userBelongsToProjectById($project, $request->user_id)
        ) {
            return response()->json([
                'message' => 'El usuario asignado no pertenece al proyecto'
            ], 422);
        }

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        return response()->json($task);
    }

    // Eliminar tarea
    public function destroy(Task $task)
    {
        $project = Project::find($task->project_task_id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        if (!$this->userBelongsToProject($project)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada'
        ]);
    }

    // Reordenar tareas
    public function reorder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array|min:1',
            'tasks.*.id_task' => 'required|integer|distinct|exists:tasks,id_task',
            'tasks.*.position' => 'required|integer|min:0'
        ]);

        $taskIds = collect($request->tasks)
            ->pluck('id_task')
            ->values();

        $tasks = Task::whereIn('id_task', $taskIds)->get();

        if ($tasks->count() !== $taskIds->count()) {
            return response()->json([
                'message' => 'Una o más tareas no existen'
            ], 404);
        }

        $projectIds = $tasks
            ->pluck('project_task_id')
            ->unique();

        if ($projectIds->count() !== 1) {
            return response()->json([
                'message' => 'Las tareas deben pertenecer al mismo proyecto'
            ], 422);
        }

        $project = Project::find($projectIds->first());

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        if (!$this->userBelongsToProject($project)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        DB::transaction(function () use ($request) {

            foreach ($request->tasks as $taskData) {

                Task::where('id_task', $taskData['id_task'])
                    ->update([
                        'position' => $taskData['position']
                    ]);
            }
        });

        return response()->json([
            'message' => 'Orden actualizado'
        ]);
    }
}
