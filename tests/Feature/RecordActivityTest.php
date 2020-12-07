<?php

namespace Tests\Feature;

use App\Activity;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();
        
        $this->assertCount(1, $project->activities);
        tap($project->activities[0], function ($activity) {
            $this->assertEquals('created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();
        $originalTitle = $project->title;
        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activities);
        tap($project->activities->last(), function ($activity) use ($originalTitle) {
            $this->assertEquals('updated', $activity->description);
            $this->assertEquals(
                [
                    'before' => ['title' => $originalTitle],
                    'after' => ['title' => 'changed']
                ],
                $activity->changes
            );
        });
    }

    /** @test */
    public function creating_a_new_task()
    {
        $project = ProjectFactory::create();
        $project->addTask('new task');
        
        $this->assertCount(2, $project->activities);
        
        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('task-created', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('new task', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks[0]->url(), [
                 'body' => 'task body',
                 'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('task-completed', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('task body', $activity->subject->body);
        });
    }


    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks[0]->url(), [
                 'body' => 'task body',
                 'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        $this->patch($project->tasks[0]->url(), [
            'body' => 'task body',
            'completed' => false
       ]);

        $project->refresh();
        $this->assertCount(4, $project->activities);
        
        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('task-incompleted', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('task body', $activity->subject->body);
        });
    }

    /** @test */
    public function delete_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        
        $project->tasks[0]->delete();


        $this->assertCount(3, $project->activities);
        $this->assertEquals('task-deleted', $project->activities->last()->description);
    }
}
