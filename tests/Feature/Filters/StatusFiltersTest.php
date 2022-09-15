<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\IdeasIndex;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\StatusFilters;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_status_filters_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $this->get(route('idea.index'))
            ->assertSeeLivewire('status-filters', [
                'statuses' => Status::all(),
                'selectedStatus' => $status->id,
            ]);
    }

    /** @test */
    public function show_page_contains_status_filters_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $status = Status::factory()->create([
            'name' => 'Open',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $this->get(route('idea.show', $idea))
            ->assertSeeLivewire('status-filters', [
                'statuses' => Status::all(),
                'selectedStatus' => $status->id,
            ]);
    }

    /** @test */
    public function shows_correct_status_count()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $statusImplemented = Status::factory()->create([
            'id' => 4,
            'name' => 'Implemented',
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        Livewire::test(StatusFilters::class)
            ->assertSee('All Ideas (2)')
            ->assertSee('Implemented (2)');
        
    }

    /** @test */
    public function filtering_works_when_query_string_in_place()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
        ]);
        $statusConsidering = Status::factory()->create([
            'name' => 'Considering',
        ]);
        $statusInProgress = Status::factory()->create([
            'name' => 'In Progress',
        ]);
        $statusImplemented = Status::factory()->create([
            'name' => 'Implemented',
        ]);
        $statusClosed = Status::factory()->create([
            'name' => 'Closed',
        ]);
        
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusConsidering->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusConsidering->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusInProgress->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusInProgress->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusInProgress->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        Livewire::withQueryParams(['status' => 'In Progress'])
            ->test(IdeasIndex::class)
            ->assertViewHas('ideas',function($ideas) {
                return $ideas->count() == 3
                    && $ideas->first()->status->name == 'In Progress';
            });
        
    }

    /** @test */
    public function show_page_does_not_show_selected_status()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $statusImplemented = Status::factory()->create([
            'id' => 4,
            'name' => 'Implemented',
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $response = $this->get(route('idea.show', $idea));

        $response->assertDontSee('border-blue text-gray-900');
        
    }
    /** @test */
    public function index_page_shows_selected_status()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Category 1',
        ]);

        $statusImplemented = Status::factory()->create([
            'id' => 4,
            'name' => 'Implemented',
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'title' => 'Idea 1',
            'description' => 'Description 1',
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSee('border-blue text-gray-900');
        
    }
}
