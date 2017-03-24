<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function anyone_can_visit_the_home_page()
    {
        $this->get('/');

        $this->assertResponseStatus(200);
        $this->see('dployr');
    }

    /** @test */
    function anyone_can_visit_the_pricing_page()
    {
        $this->get('/pricing');

        $this->assertResponseStatus(200);
    }

    /** @test */
    function anyone_can_visit_the_about_page()
    {
        $this->get('/about');

        $this->assertResponseStatus(200);
    }

    /** @test */
    function a_user_must_be_logged_in_to_visit_the_dashboard()
    {
        $this->get('/myaccount');

        $this->assertResponseStatus(302);
        $this->followRedirects();

        $this->seePageIs('/login');
    }

    /** @test */
    function a_user_can_see_the_dashboard()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->get('/myaccount');

        $this->assertResponseStatus(200);
    }
}
