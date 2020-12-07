<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_can_not_add_task_to_projects()
    {
        $project = factory(Project::class)->create();

        $this->post($project->url() . '/tasks', [])->assertRedirect('login');
    }

    /** @test */
    public function only_owner_of_the_project_can_add_a_task()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $body = $this->faker->sentence(2);
        $this->post($project->url(). '/tasks', ['body' => $body])
                ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => $body]);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $body = $this->faker->sentence(3);
        $this->actingAs($project->owner)
                ->post($project->url(). '/tasks', ['body' => $body])
                ->assertRedirect($project->url());

        $this->get($project->url())
                ->assertSee($body);
    }
    
    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $newAttributes = [
            'body' => 'changed',
            'completed' => true
        ];
        $this->actingAs($project->owner)
                ->patch($project->tasks[0]->url(), $newAttributes)
                ->assertRedirect($project->url());

        $this->assertDatabaseHas('tasks', $newAttributes);
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $project = ProjectFactory::withTasks(1)->create(['completed' => true]);
        
        $attributes = ['body' => 'changed', 'completed' => false];
        $this->actingAs($project->owner)
                ->patch($project->tasks[0]->url(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function only_owner_of_the_project_can_update_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->url(), $attributes = ['body' => 'changed'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function a_task_requires_body()
    {
        $attributes = factory(Task::class)->raw(['body' => '']);
        
        $project = ProjectFactory::create();


        $this->actingAs($project->owner)->post($project->url() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
