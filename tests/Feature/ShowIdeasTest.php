<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_ideas_shows_on_main_page()
    {
        $ideaOne = Idea::factory()->create([
            'title' => 'Idea One',
            'description' => 'Description of my first title',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'Idea Two',
            'description' => 'Description of my second title',
        ]);


        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();

        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);

        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
    }

    /** @test */

    public function single_idea_shows_correctly_on_the_show_page()
    {
        $idea = Idea::factory()->create([
            'title' => 'Idea !!!',
            'description' => 'Description of my title',
        ]);


        $response = $this->get(route('idea.show',$idea));

        $response->assertSuccessful();

        $response->assertSee($idea->title);
        $response->assertSee($idea->description);

    }

    /** @test */

    public function ideas_pagination_works()
    {
        Idea::factory(Idea::PAGINATION_COUNT + 1 )->create();

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
        $ideaOne = Idea::factory()->create([
            'title' => 'Same Idea',
            'description' => 'Same Description',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'Same Idea',
            'description' => 'Same Description',
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'Same Idea',
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
