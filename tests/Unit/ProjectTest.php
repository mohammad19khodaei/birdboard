<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_a_url()
    {
        $project = factory(Project::class)->create();

        $this->assertEquals('/projects/'. $project->id, $project->url());
    }

    /** @test */
    public function it_has_owner()
    {
        $project = factory(Project::class)->create();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = factory(Project::class)->create();

        $task = $project->addTask('test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    public function it_can_invite_a_new_user()
    {
        $project = ProjectFactory::create();

        $project->invite($user = factory(User::class)->create());

        $this->assertTrue($project->checkMember($user));
    }
}
