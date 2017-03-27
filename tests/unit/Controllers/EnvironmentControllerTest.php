<?php

use App\Models\User;
use App\Models\Group;
use App\Models\Repository;
use App\Models\Environment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EnvironmentControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_edit_an_environment()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $user->group_id = factory(Group::class)->create()->id;
        $user->save();

        $environment = factory(Environment::class)->create();

        $user->group->repositories()->save(Repository::find($environment->repository_id));

        $this->visit('/environment/'.$environment->id)
            ->see('Example Repo - Default Environment');
    }

    /** @test */
    function a_user_cant_edit_an_environment_that_doesnt_exist()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->visit('/environment/5')
            ->see('The specified evironment couldn\'t be found');
    }
}
