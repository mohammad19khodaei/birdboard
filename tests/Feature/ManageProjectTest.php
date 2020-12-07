<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ManageProjectTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    /** @test */
    public function guests_cannot_create_projects()
    {
        $attributes = factory(Project::class)->raw();

        $this->post('projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_projects()
    {
        $this->get('projects')->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_create_project_page()
    {
        $this->get('projects/create')->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_a_single_project()
    {
        $project = factory(Project::class)->create();

        $this->get($project->url())->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->url())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();
        
        $this->actingAs($project->owner)
            ->delete($project->url())
            ->assertRedirect('projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function unauthorized_user_can_not_delete_a_project()
    {
        $project = ProjectFactory::create();

        $user = $this->signIn();

        $this->delete($project->url())
            ->assertStatus(403);

        $project->invite($user);
        
        $this->delete($project->url())
        ->assertStatus(403);
    }
    
    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();
        $this->get('projects/create')->assertStatus(200);

        $attributes = factory(Project::class)->raw();
        
        $this->followingRedirects()->post('projects', $attributes)
                ->assertSee($attributes['title'])
                ->assertSee(Str::limit($attributes['description']))
                ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $attributes = [
            'title' => 'changed',
            'description' => 'changed',
            'notes' => 'changed'
        ];
        $this->actingAs($project->owner)
                ->patch($project->url(), $attributes)
                ->assertRedirect($project->url());

        $this->get($project->url() . '/edit')->assertOK();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_a_project_general_notes()
    {
        $project = ProjectFactory::create();


        $this->actingAs($project->owner)
                ->patch($project->url(), $attributes = ['notes' => 'changed'])
                ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('projects', array_merge($attributes, [
            'title' => $project->title,
            'description' => $project->description
        ]));
    }



    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        
        $attributes = factory(Project::class)->raw(['title' => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['description' => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors(['description']);
    }

    /** @test */
    public function users_can_view_their_projects()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->url())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_single_project_of_others()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->get($project->url())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_project_of_others()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->patch($project->url(), $attributes = ['notes' => 'changed'])->assertStatus(403);

        $this->assertDatabaseMissing('projects', $attributes);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_projects_of_others()
    {
        $this->signIn();

        $project = factory(Project::class)->create();


        $this->get('projects')
                ->assertDontSee($project->title)
                ->assertDontSee($project->description);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_his_dashboard()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $project = tap(ProjectFactory::create())->invite($user);


        $this->get('/projects')
            ->assertSee($project->title);
    }
}
