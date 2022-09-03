<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\IdeaShow;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteShowPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_idea_show_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);
        $this->get(route('idea.show', $idea))
            ->assertSeeLivewire('idea-show');
    }

    /** @test */
    public function show_page_corectly_receive_votes_count()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

        Vote::factory()->create([
            'user_id' => $userB->id,
            'idea_id' => $idea->id,
        ]);

        $this->get(route('idea.show', $idea))
            ->assertViewHas('votesCount', 2);
    }

    /** @test */
    public function votes_count_shows_correctly_on_show_page_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);

        Livewire::test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->assertSet('votesCount', 5);
    }

    /** @test */
    public function user_who_is_logged_in_shows_voted_if_idea_already_voted_for()
    {
        User::factory()->create();
        $user = User::find(1);

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);


        Livewire::actingAs($user) 
            ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }

    /** @test */
    public function user_who_is_not_logged_in_is_redirected_to_login_page_when_trying_to_vote()
    {
        User::factory()->create();
        $user = User::find(1);

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);


        Livewire::test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->call('vote')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_who_is_logged_in_can_vote_for_idea()
    {
        User::factory()->create();
        $user = User::find(1);

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);


        Livewire::actingAs($user)
            ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->call('vote')
            ->assertSet('votesCount', 6)
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

    }

    /** @test */
    public function user_who_is_logged_in_can_remove_vote_for_idea()
    {
        User::factory()->create();
        $user = User::find(1);

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Idea One',
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => 'Description of my first title',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->call('vote')
            ->assertSet('votesCount', 4)
            ->assertSet('hasVoted', false)
            ->assertSee('Vote');

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

    }
    
}
