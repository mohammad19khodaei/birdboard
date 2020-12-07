<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use App\Http\Controllers\ProjectTaskController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function owner_of_a_project_can_invite_another_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $newUser = factory(User::class)->create();

        $this->actingAs($project->owner)
                ->post($project->url() . '/invitation', ['email' => $newUser->email])
                ->assertRedirect($project->url());

        $this->assertTrue($project->checkMember($newUser));
    }

    /** @test */
    public function the_email_address_must_be_assossiacted_with_a_valid_birdboard_account()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->url(). '/invitation', ['email' => 'notexists@email.com'])
            ->assertSessionHasErrors();

        $this->assertEquals('Email address is not valid.', session('errors')->invitation->get('email')[0]);
    }

    /** @test */
    public function just_owner_of_the_project_can_invite_a_new_user()
    {
        $project = ProjectFactory::create();

        $noneProjectOwner = factory(User::class)->create();

        $this->actingAs($noneProjectOwner)
                ->post($project->url(). '/invitation')
                ->assertStatus(403);

        $project->invite($noneProjectOwner);

        $this->actingAs($noneProjectOwner)
                ->post($project->url(). '/invitation')
                ->assertStatus(403);
    }

    /** @test */
    public function new_member_of_the_project_can_update_project_details()
    {
        $project = tap(ProjectFactory::create())->invite($newUser = factory(User::class)->create());

        $this->actingAs($newUser)
                ->post(action([ProjectTaskController::class, 'store'], $project), $task = ['body' => 'test task'])
                ->assertRedirect($project->url());
        
        $this->assertDatabaseHas('tasks', $task);

        $this->patch($project->url(), ['title' => 'updated']);
        $this->assertDatabaseHas('projects', ['title' => 'updated']);
    }
}
