<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;



class ProjectController extends Controller
{
    public function index(){
        return Project::with(['users', 'tasks'])->get();
    }

    public function store(Request $request){
        return Project::create($request->all());
    }

    public function destroy(Project $project){
        $project->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }

    public function update(Request $request, Project $project){
        $project->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return response()->json($project);
    }
    public function addUser(Request $request, Project $project){
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

    public function removeUser(Project $project, User $user)
    {
        $project->users()->detach($user->id_user);

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }

}
