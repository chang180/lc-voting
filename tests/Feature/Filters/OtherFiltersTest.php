<?php

namespace Tests\Feature\Filters;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_voted_filter_works()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 2',
            'description' => 'Description 2',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $ideaOne->id,
        ]);

        Vote::factory()->create([
            'user_id' => $userB->id,
            'idea_id' => $ideaOne->id,
        ]);
        Vote::factory()->create([
            'user_id' => $userC->id,
            'idea_id' => $ideaTwo->id,
        ]);


        Livewire::test(IdeasIndex::class)
            ->set('filter', 'Top Voted')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->votes()->count() === 2
                    && $ideas->get(1)->votes()->count() === 1;
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 2',
            'description' => 'Description 2',
        ]);
        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);


        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->title === 'Idea 2'
                    && $ideas->get(1)->title === 'Idea 1';
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 2',
            'description' => 'Description 2',
        ]);
        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);


        Livewire::test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertRedirect('login');
    }

    /** @test */
    public function my_ideas_filter_works_correctly_with_category_filter()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 2',
            'description' => 'Description 2',
        ]);
        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);


        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->set('category', 'Category 1')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->title === 'Idea 2'
                    && $ideas->get(1)->title === 'Idea 1';
            });
    }

    /** @test */
    public function no_filter_works_correctly()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 2',
            'description' => 'Description 2',
        ]);
        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);


        Livewire::test(IdeasIndex::class)
            ->set('filter', 'No Filter')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 3
                    && $ideas->first()->title === 'Idea 3'
                    && $ideas->get(2)->title === 'Idea 1';
            });
    }

    /** @test */
    public function spam_ideas_filter_works()
    {
        $user = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            'spam_reports' => 1,
            'title' => 'Idea 1',
        ]);

        $ideaTwo = Idea::factory()->create([
            'spam_reports' => 2,
            'title' => 'Idea 2',
        ]);

        $ideaThree = Idea::factory()->create([
            'spam_reports' => 3,
            'title' => 'Idea 3',
        ]);

        $ideaFour = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'Spam Ideas')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 3
                    && $ideas->first()->title === 'Idea 3'
                    && $ideas->get(1)->title === 'Idea 2'
                    && $ideas->get(2)->title === 'Idea 1';
            });
    }

    /** @test */
    public function spam_comments_filter_works()
    {
        $user = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            'title' => 'Idea 1',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'Idea 2',
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'Idea 3',
        ]);

        $commentOne = Comment::factory()->create([
            'idea_id' => $ideaOne->id,
        ]);

        $commentTwo = Comment::factory()->create([
            'idea_id' => $ideaTwo->id,
            'spam_reports' => 2,
        ]);

        $commentThree = Comment::factory()->create([
            'idea_id' => $ideaThree->id,
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'Spam Comments')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->title === 'Idea 3'
                    && $ideas->get(1)->title === 'Idea 2';
            });
    }

}
