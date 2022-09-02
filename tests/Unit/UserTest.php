<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_check_if_a_user_is_an_admin()
    {
        $user = User::factory()->make([
            'name' => 'test',
            'email' => 'test@test.com',
        ]);

        $userB = User::factory()->make([
            'name' => 'test_not_admin',
            'email' => 'test_note_admin@test.com',
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($userB->isAdmin());
    }
}
