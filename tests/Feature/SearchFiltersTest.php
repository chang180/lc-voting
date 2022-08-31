<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\IdeasIndex;
use App\Models\Vote;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function searching_works_when_more_than_3_characters()
    {
        $user = User::factory()->create();

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
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $ideaOne->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('search', 'ea 2')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 1
                    && $ideas->first()->title === 'Idea 2';
            });
    }

    /** @test */
    public function does_not_perform_search_if_less_than_3_characters()
    {
        $user = User::factory()->create();

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
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'Idea 3',
            'description' => 'Description 3',
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $ideaOne->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('search', 'ea')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 3;
            });
    }

    /** @test */
    public function searching_works_correcly_with_category_filters()
    {
        $user = User::factory()->create();

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
            ->set('category', 'Category 1')
            ->set('search', 'Idea')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2;
            });
    }

}
