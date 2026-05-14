<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller{

    // Comprobación del usuario en el proyecto
    private function userBelongsToProject(Project $project){
            $user = Auth::user();

            return $project->users()
                ->where('users.id_user', $user->id_user)
                ->exists();
        }

    //  Obtener tareas por proyecto
    public function index(Project $project){

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return $project->tasks()
            ->orderBy('position')
            ->get();
    }

    //  Crear tarea
        public function store(Request $request){

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
            'user_id' => 'nullable|exists:users,id_user'
        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'project_task_id' => $request->project_task_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($task);
    }

    //  Actualizar
        public function update(Request $request, Task $task){

        $project = Project::find($task->project_task_id);

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $request->validate([
            'user_id' => 'nullable|exists:users,id_user'
        ]);

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

    //  Eliminar
    public function destroy(Task $task){

        $project = Project::find($task->project_task_id);

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

    public function reorder(Request $request){

        foreach ($request->tasks as $taskData) {

            Task::where('id_task', $taskData['id_task'])
                ->update([
                    'position' => $taskData['position']
                ]);
        }

        return response()->json([
            'message' => 'Orden actualizado'
        ]);
    }
}
