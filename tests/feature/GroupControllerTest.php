<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GroupControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_create_a_group()
    {
        $user = new User([
            'name' => "Sample User",
            'email' => "test@example.org",
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);

        $this->actingAs($user);

        $this->assertNull($user->group);

        $this->post('/group', [
            'name' => "Test Group",
        ]);

        $this->assertNotNull($user->fresh()->group);
    }
}
