<?php

namespace App\Http\Controllers;

use App\Models\Project;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function store(Request $request)
    {
        return Project::create([
            'name' => $request->name,
            'user_id' => 1
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
