<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Project;
use App\User;

class ProjectMemberController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $project->invite(User::query()->whereEmail($request->email)->first());

        return redirect($project->url());
    }
}
