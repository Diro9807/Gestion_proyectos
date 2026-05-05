<?php

namespace App\Http\Controllers;

use App\Models\Project;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with(['users', 'tasks'])->get();
    }

    public function store(Request $request)
    {
        return Project::create($request->all());
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }

    public function update(Request $request, Project $project)
{
        $project->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

    return response()->json($project);
}
}
