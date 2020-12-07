<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Collection;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_has_projects()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }


    /** @test */
    public function it_has_all_projects()
    {
        $john = $this->signIn();

        ProjectFactory::withOwner($john)->create();

        $this->assertCount(1, $john->allProjects());
        
        $sally = factory(User::class)->create();
        $lionel = factory(User::class)->create();
        
        $project = tap(ProjectFactory::withOwner($sally)->create())->invite($lionel);
        
        $this->assertCount(1, $john->allProjects());
        
        $project->invite($john);
        
        $this->assertCount(2, $john->allProjects());
    }

    /** @test */
    public function it_has_gravatar_url()
    {
        $user = factory(User::class)->create(['email' => 'mohammad19khodaei@gmail.com']);
        
        $expectedUrl = 'https://gravatar.com/avatar/f6a685b80526d74be645c513fa57dd9d?s=60';
        $this->assertEquals($expectedUrl, $user->gravatarUrl());
    }
}
