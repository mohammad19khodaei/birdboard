<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_have_url()
    {
        $task = factory(Task::class)->create();


        $this->assertEquals("/projects/{$task->project->id}/tasks/{$task->id}", $task->url());
    }

    /** @test */
    public function a_task_belongs_to_a_project()
    {
        $project = ProjectFactory::create();
        $task = factory(Task::class)->create(['project_id' => $project->id]);

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function a_task_can_complete()
    {
        $task = factory(Task::class)->create();
        $this->assertFalse($task->completed);


        $task->complete();
        $this->assertTrue($task->fresh()->completed);
    }


    /** @test */
    public function a_task_can_marked_as_incomplete()
    {
        $task = factory(Task::class)->create(['completed' => true]);
        $this->assertTrue($task->completed);

        $task->incomplete();
        $this->assertFalse($task->refresh()->completed);
    }
}
