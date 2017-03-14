<?php

use App\Jobs\CloneRepository;
use App\Jobs\DeleteRepository;
use App\Models\Plan;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_list_their_own_repositories()
    {
        $user = factory(User::class)->create();
        $usersRepo = new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]);
        $user->group->repositories()->save($usersRepo);

        $notUsersRepo = new Repository([
            'name' => "Other repo",
            'url' => 'git@example.com:example/other.git'
        ]);
        $notUsersRepo->group_id = 2;
        $notUsersRepo->save();

        $this->actingAs($user);

        $this->get('/repository');

        $this->see("Example repository");
        $this->dontSee("Other repo");
    }

    /** @test */
    function a_user_can_add_a_repository_that_starts_with_git()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->expectsJobs(CloneRepository::class);

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
        $this->doesntExpectJobs(CloneRepository::class);

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
        $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->actingAs($user);
        $this->doesntExpectJobs(CloneRepository::class);

        $this->post('/repository/new', [
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]);

        $this->followRedirects();
        $this->see("You have already connected that repository.");

        $this->assertEquals(1, $user->fresh()->group->repositories->count());
    }

    /** @test */
    function a_user_cannot_add_a_repository_if_they_are_at_the_repository_limit()
    {
        $user = factory(User::class)->create();
        $user->group->plan_id = factory(Plan::class)->create([
                "repository_limit" => 1
            ])->id;
        $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->actingAs($user);
        $this->doesntExpectJobs(CloneRepository::class);

        $this->post('/repository/new', [
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo2.git',
        ]);

        $this->followRedirects();
        $this->see("You are at your repository limit");

        $this->assertEquals(1, $user->fresh()->group->repositories->count());
    }

    /** @test */
    function a_user_can_edit_a_repository_that_exists()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->actingAs($user);

        $this->get('/repository/'.$repo->id);

        $this->assertResponseStatus(200);
        $this->see($repo->name);
    }

    /** @test */
    function a_user_cannot_edit_a_repository_that_does_not_exist()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user);

        $this->get('/repository/187');

        $this->followRedirects();

        $this->see("The specified repository couldn't be found");
    }

    /** @test */
    function a_user_can_delete_a_repository_that_exists()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->actingAs($user);
        $this->expectsJobs(DeleteRepository::class);

        $this->delete('/repository/'.$repo->id);

        $this->assertResponseStatus(200);
    }

    /** @test */
    function a_user_cannot_delete_a_repository_that_doesnt_exist()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user);
        $this->doesntExpectJobs(DeleteRepository::class);

        $this->delete('/repository/123');

        $this->assertResponseStatus(422);
    }

    /** @test */
    function a_user_cannot_delete_a_repository_that_is_initialising()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));
        $repo->status = Repository::STATUS_INITIALISING;
        $repo->save();

        $this->actingAs($user);
        $this->doesntExpectJobs(DeleteRepository::class);

        $this->delete('/repository/'.$repo->id);

        $this->assertResponseStatus(422);
    }

    /** @test */
    function a_user_can_download_their_repositorys_public_key()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));

        $this->actingAs($user);

        $this->get("/repository/$repo->id/key");

        $this->assertResponseStatus(200);
    }

    /** @test */
    function a_user_cannot_download_a_public_key_for_a_repository_that_doesnt_exist()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user);

        $this->get("/repository/123/key");

        $this->followRedirects();
        $this->see("Couldn't find the specified repository");
    }

    /** @test */
    function a_user_can_reinitialise_their_repository_if_it_failed()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));
        $repo->status = Repository::STATUS_ERROR;
        $repo->save();

        $this->actingAs($user);
        $this->expectsJobs(CloneRepository::class);

        $this->get("/repository/$repo->id/initialise");

        $this->followRedirects();
        $this->see("Your repository has been queued for initialisation");
    }

    /** @test */
    function a_user_cannot_initialise_a_repository_that_doesnt_exist()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user);
        $this->doesntExpectJobs(CloneRepository::class);

        $this->get("/repository/123/initialise");

        $this->followRedirects();
        $this->see("Couldn't find the specified repository");
    }

    /** @test */
    function a_user_cannot_initialise_a_repository_that_is_initialising()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));
        $repo->status = Repository::STATUS_INITIALISING;
        $repo->save();
        
        $this->actingAs($user);
        $this->doesntExpectJobs(CloneRepository::class);

        $this->get("/repository/$repo->id/initialise");

        $this->followRedirects();
        $this->see("Your repository is already initialising");
    }

    /** @test */
    function a_user_cannot_initialise_a_repository_that_is_already_initialised()
    {
        $user = factory(User::class)->create();
        $repo = $user->group->repositories()->save(new Repository([
            'name' => "Example repository",
            'url' => 'git@example.com:example/repo.git',
        ]));
        
        $this->actingAs($user);
        $this->doesntExpectJobs(CloneRepository::class);

        $this->get("/repository/$repo->id/initialise");

        $this->followRedirects();
        $this->see("Your repository is already initialised");
    }
}
