<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeaIndex;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_idea_index_livewire_component()
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
        $this->get(route('idea.index'))
            ->assertSeeLivewire('idea-index');
    }

    /** @test */
    public function index_page_correctly_receive_votes_count()
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

        $this->get(route('idea.index'))
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->first()->votes_count === 2;
            });
    }

    /** @test */
    public function votes_count_shows_correctly_on_index_page_livewire_component()
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

        Livewire::test(IdeaIndex::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])
            ->assertSet('votesCount', 5)
            ->assertSee('<div class="font-semibold text-2xl ">5</div>', false)
            ->assertSee('<div class="text-sm font-bold leading-none  ">5</div>', false);
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

        $response = $this->actingAs($user)
            ->get(route('idea.index'));

        $ideaWithVotes = $response['ideas']->items()[0];

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, [
                'idea' => $ideaWithVotes,
                'votesCount' => 5,
            ])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }
}
