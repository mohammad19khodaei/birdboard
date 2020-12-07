<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('access', $project);
        
        request()->validate([
            'body' => ['required', 'string']
        ]);
        $project->addTask(request('body'));

        return redirect($project->url());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('access', $task->project);
        
        
        $task->update(request()->validate(['body' => ['required', 'string']]));
        

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->url());
    }
}
