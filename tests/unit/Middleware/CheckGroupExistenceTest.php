<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CheckGroupExistenceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_visit_the_group_creation_page()
    {
        $user = new User([
            'name' => "Sample User",
            'email' => "test@example.org",
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);

        $this->actingAs($user);

        $this->visit('/myaccount');
        $this->seePageIs('/group');
    }
}
