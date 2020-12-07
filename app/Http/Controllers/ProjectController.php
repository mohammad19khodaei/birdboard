<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;

class ProjectController extends Controller
{
    
    public function index()
    {
        $projects = auth()->user()->allProjects();

        return view('projects.index', compact('projects'));
    }

    
    public function create()
    {
        return view('projects.create');
    }

   
    public function store(CreateProjectRequest $request)
    {
        $project = auth()->user()->projects()->create($request->validated());

        return redirect($project->url());
    }

    
    public function show(Project $project)
    {
        $this->authorize('access', $project);
        return view('projects.show', compact('project'));
    }

   
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    
    public function update(Project $project, UpdateProjectRequest $request)
    {
        $project->update($request->validated());

        return redirect($project->url());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        
        $project->delete();
        return redirect('projects');
    }
}
