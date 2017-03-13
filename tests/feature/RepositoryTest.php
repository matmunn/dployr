<?php

use App\Models\User;
use App\Models\Repository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_add_a_repository_that_starts_with_git()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->post('/repository/new', [
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]);

        $this->followRedirects();

        $this->see('Your repository has been queued for initialisation');

        $this->assertEquals(1, $user->fresh()->group->repositories->count());
    }

    /** @test */
    function a_user_cannot_add_a_repository_that_starts_with_https()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->post('/repository/new', [
            'name' => "Example repository",
            'url' => 'https://example.com:example/repo.git',
        ]);

        $this->followRedirects();
        $this->see("HTTPS login is currently unsupported, please use SSH URLs");

        $this->assertEquals(0, $user->fresh()->group->repositories->count());
    }

    /** @test */
    function a_user_cannot_add_the_same_repository_twice()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->post('/repository/new', [
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]);

        $this->followRedirects();
        $this->see("You have already connected that repository.");

        $this->assertEquals(1, $user->fresh()->group->repositories->count());
    }
}
