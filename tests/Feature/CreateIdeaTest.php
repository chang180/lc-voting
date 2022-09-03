<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\CreateIdea;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_idea_form_does_not_show_when_logged_out()
    {
        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee('Please login to create an idea.');
        $response->assertDontSee("Let us know what you would lik and we'll take a look up!!");
    }

    /** @test */
    public function create_idea_form_does_show_when_logged_in()
    {
        User::factory()->create();
        $response = $this->actingAs(User::find(1))->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertDontSee('Please login to create an idea.');
        $response->assertSee('Let us know what you would lik and we\'ll take a look up!!',false);
    }

    /** @test */
    public function main_page_contains_create_idea_live_component()
    {
        User::factory()->create();
        $this->actingAs(User::find(1))
            ->get(route('idea.index'))
            ->assertSeeLivewire('create-idea');

    }

    /** @test */
    public function create_idea_form_validation_works()
    {
        User::factory()->create();
        Livewire::actingAs(User::find(1))
            ->test(CreateIdea::class)
            ->set('title','')
            ->set('category','')
            ->set('description','')
            ->call('createIdea')
            ->assertHasErrors(['title','category','description'])
            ->assertSee('validation.required');
    }

    /** @test */
    public function creating_an_idea_works_correctly()
    {
        User::factory()->create();
        $user = User::find(1);

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','Idea Title')
            ->set('category',$categoryOne->id)
            ->set('description','Idea Description')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $response = $this->actingAs($user)->get(route('idea.index'));
        $response->assertSuccessful();
        $response->assertSee('Idea Title');
        $response->assertSee('Idea Description');

        $this->assertDatabaseHas('ideas',[
            'title' => 'Idea Title',
        ]);
        $this->assertDatabaseHas('votes',[
            'user_id' => $user->id,
            'idea_id' => 1,
        ]);

    }

    /** @test */
    public function creating_two_ideas_with_same_title_still_works_but_has_different_slugs()
    {
        User::factory()->create();
        $user = User::find(1);

        $categoryOne = Category::factory()->create([
            'name' => 'Category 1',
        ]);
        $categoryTwo = Category::factory()->create([
            'name' => 'Category 2',
        ]);

        $statusOpen = Status::factory()->create([
            'name' => 'Open',
            'classes' => 'bg-gray-200',
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','Idea Title')
            ->set('category',$categoryOne->id)
            ->set('description','Idea Description')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $this->assertDatabaseHas('ideas',[
            'title' => 'Idea Title',
            'slug' => 'idea-title',
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','Idea Title')
            ->set('category',$categoryOne->id)
            ->set('description','Idea Description')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $this->assertDatabaseHas('ideas',[
            'title' => 'Idea Title',
            'slug' => 'idea-title-2',
        ]);

    }
}
