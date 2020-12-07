<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectTaskController;

Route::group(['middleware' => 'auth'], function () {
    // Manage projects routes
    Route::get('/', [ProjectController::class, 'index']);
    Route::resource('projects', 'ProjectController');

    // Manage project tasks routes
    Route::post('projects/{project}/tasks', [ProjectTaskController::class, 'store']);
    Route::patch('projects/{project}/tasks/{task}', [ProjectTaskController::class, 'update']);

    // Invite to project route
    Route::post('projects/{project}/invitation', [ProjectMemberController::class, 'store']);
});

Auth::routes();
