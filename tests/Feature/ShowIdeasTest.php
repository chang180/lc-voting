<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_ideas_shows_on_main_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering' , 'classes' => 'bg-purple text-white']);

        $ideaOne = Idea::factory()->create([
            'title' => 'Idea One',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my first title',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'Idea Two',
            'category_id' => $categoryTwo->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my second title',
        ]);


        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();

        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($ideaOne->category->name);
        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 px-4 py-2">Open</div>',false);
        
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
        $response->assertSee($ideaTwo->category->name);
        $response->assertSee('<div class="bg-purple text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 px-4 py-2">Considering</div>',false);
    }

    /** @test */

    public function single_idea_shows_correctly_on_the_show_page()
    {
        $category = Category::factory()->create(['name' => 'Category 1']); 

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        
        $idea = Idea::factory()->create([
            'title' => 'Idea !!!',
            'category_id' => $category->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my title',
        ]);


        $response = $this->get(route('idea.show',$idea));
        $response->assertSuccessful();

        $response->assertSee($idea->title);
        $response->assertSee($idea->category->name);
        $response->assertSee($idea->description);
        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 px-4 py-2">Open</div>',false);

    }

    /** @test */

    public function ideas_pagination_works()
    {
        $category = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        Idea::factory(Idea::PAGINATION_COUNT + 1 )->create([
            'category_id' => $category->id,
            'status_id' => $statusOpen->id,
        ]);

        $ideaOne = Idea::find(1);
        $ideaOne->title = "My First Idea";
        $ideaOne->save();

        $ideaEleven = Idea::find(11);
        $ideaEleven->title = "My 11th Idea";
        $ideaEleven->save();

        $response = $this->get('/');

        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaEleven->title);


        $response = $this->get('/?page=2');

        $response->assertSee($ideaEleven->title);
        $response->assertDontSee($ideaOne->title);
    }

    /** @test */
    public function same_idea_title_different_slugs()
    {
        $category = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $ideaOne = Idea::factory()->create([
            'title' => 'Same Idea',
            'category_id' => $category->id,
            'status_id' => $statusOpen->id,
            'description' => 'Same Description',
        ]);

        $ideaTwo = Idea::factory()->create([ 
            'title' => 'Same Idea',
            'category_id' => $category->id,
            'status_id' => $statusOpen->id,
            'description' => 'Same Description',
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'Same Idea',
            'category_id' => $category->id,
            'status_id' => $statusOpen->id,
            'description' => 'Same Description',
        ]);

        $response = $this->get(route('idea.show',$ideaOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/same-idea');

        $response = $this->get(route('idea.show',$ideaTwo));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/same-idea-2');

        $response = $this->get(route('idea.show',$ideaThree));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/same-idea-3');

    }

}
