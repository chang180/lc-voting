<?php

namespace Tests\Feature;

use App\Http\Livewire\SetStatus;
use App\Jobs\NotifyAllVoters;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

class AdminSetStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_set_status_livewire_component_when_user_is_admin()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('set-status');
    }

    /** @test */
    public function show_page_cantains_set_status_livewire_component_when_user_is_logged_in()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);
        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my first title',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('set-status');
    }

    /** @test */
    public function show_page_does_not_cantains_set_status_livewire_component_when_user_is_not_admin()
    {
        User::factory()->create([
            'email' => 'test_not_admin@test.com',
        ]);
        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my first title',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('set-status');
    }

    /** @test */
    public function initial_status_is_set_correctly()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first title',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->assertSet('status', $statusConsidering->id);
    }

    /** @test */
    public function can_set_status_correctly_no_comment()
    {
        User::factory()->admin()->create();

        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['id' => 1, 'name' => 'Open']);
        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['id' => 3, 'name' => 'In Progress']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first title',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->set('status', $statusInProgress->id)
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'status_id' => $statusInProgress->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'Status changed to '.$statusInProgress->name,
            'is_status_update' => 1,
        ]);
    }

    /** @test */
    public function can_set_status_correctly_with_comment()
    {
        User::factory()->admin()->create();

        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['id' => 1, 'name' => 'Open']);
        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['id' => 3, 'name' => 'In Progress']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first title',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->set('status', $statusInProgress->id)
            ->set('comment', 'This is a comment')
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'status_id' => $statusInProgress->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'This is a comment',
            'is_status_update' => 1,
        ]);

    }

    /** @test */
    public function can_set_status_correctly_while_notifying_all_voters()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $user = User::find(1);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['id' => 1, 'name' => 'Open']);
        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['id' => 3, 'name' => 'In Progress']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first title',
        ]);

        Queue::fake();

        Queue::assertNothingPushed();

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->set('status', $statusInProgress->id)
            ->set('notifyAllVoters', true)
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        Queue::assertPushed(NotifyAllVoters::class);
    }
}
