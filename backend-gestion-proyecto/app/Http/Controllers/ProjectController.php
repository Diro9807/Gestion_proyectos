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

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

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

    public function destroy($id){

        $project = Project::findOrFail($id);

        if ($response = $this->checkProjectOwner($project)) {
            return $response;
        }

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado'
        ]);
    }

    public function update(Request $request, $id){

        $project = Project::findOrFail($id);

        if ($response = $this->checkProjectOwner($project)) {
            return $response;
        }

        $project->update($request->only([
            'name',
            'description'
        ]));

        return response()->json($project);
    }

    public function addUser(Request $request, $id){

        try {

            $project = Project::findOrFail($id);

            if ($response = $this->checkProjectOwner($project)) {
                return $response;
            }

            $request->validate([
                'user_id' => 'required|exists:users,id_user',
                'role' => 'nullable|in:admin,member'
            ]);

            $alreadyExists = $project->users()
                ->where('users.id_user', $request->user_id)
                ->exists();

            if ($alreadyExists) {

                return response()->json([
                    'message' => 'El usuario ya pertenece al proyecto'
                ], 409);
            }

            $project->users()->attach(
                $request->user_id,
                [
                    'role' => $request->role ?? 'member'
                ]
            );

            return response()->json([
                'message' => 'Usuario añadido'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function removeUser($projectId, $userId){

        $project = Project::findOrFail($projectId);

        if ($userId == Auth::user()->id_user) {
            return response()->json([
                'message' => 'No puedes eliminarte a ti mismo'
            ], 403);
        }

        if ($response = $this->checkProjectOwner($project)) {
            return $response;
        }

        $project->users()->detach($userId);

        $targetUser = $project->users()
            ->where('users.id_user', $userId)
            ->first();

        if ($targetUser?->pivot?->role === 'owner') {

            return response()->json([
                'message' => 'No puedes eliminar el owner'
            ], 403);
        }

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }



    private function userRole(Project $project){

        $user = Auth::user();

        $relation = $project->users()
            ->where('users.id_user', $user->id_user)
            ->first();

        return $relation?->pivot?->role;
    }

    private function hasProjectRole(Project $project, array $roles){

        $role = $this->userRole($project);

        return in_array($role, $roles);
    }

    public function changeRole(Request $request, $projectId, $userId){

        $project = Project::findOrFail($projectId);

        $request->validate([
            'role' => 'required|in:admin,member'
        ]);

        if ($response = $this->checkProjectOwner($project)) {
            return $response;
        }

        $targetUser = $project->users()
            ->where('users.id_user', $userId)
            ->first();

        if ($targetUser?->pivot?->role === 'owner') {

            return response()->json([
                'message' => 'No puedes modificar el owner'
            ], 403);
        }

        $project->users()->updateExistingPivot(
            $userId,
            ['role' => $request->role]
        );

        return response()->json([
            'message' => 'Rol actualizado'
        ]);
    }

    public function ownedProjects(){
        $user = Auth::user();

        $projects = $user->projects()
            ->wherePivot('role', 'owner')
            ->with(['users', 'tasks'])
            ->get();

        return response()->json($projects);
    }

    public function sharedProjects(){

        $user = Auth::user();

        $projects = $user->projects()
            ->wherePivotIn('role', ['admin', 'member'])
            ->with(['users', 'tasks'])
            ->get();

        return response()->json($projects);
    }

    private function checkProjectOwner(Project $project){

        if (!$this->hasProjectRole($project, ['owner'])) {

            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return null;
    }



}

