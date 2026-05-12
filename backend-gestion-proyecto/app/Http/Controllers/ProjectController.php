<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{


    public function index(){

        $user = Auth::user();

        $projects = $user->projects()
            ->with(['users', 'tasks'])
            ->get();

        return response()->json($projects);
    }

    public function store(Request $request){
        $user = Auth::user();

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $project->users()->attach($user->id_user, [
            'role' => 'owner'
        ]);

        return response()->json(
            $project->load('users'),
            201
        );
    }

    public function destroy(Project $project){

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado'
        ]);
    }

    public function update(Request $request, Project $project){

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json($project);
    }

    public function addUser(Request $request, Project $project){

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $project->users()->syncWithoutDetaching([
            $user->id_user => [
                'role' => $request->role ?? 'member'
            ]
        ]);

        return response()->json([
            'message' => 'Usuario añadido'
        ]);
    }

    public function removeUser(Project $project, User $user){

        if (!$this->userBelongsToProject($project)) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $project->users()->detach($user->id_user);

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }

    private function userBelongsToProject(Project $project){

        $user = Auth::user();

        return $project->users()
            ->where('users.id_user', $user->id_user)
            ->exists();
    }

}
