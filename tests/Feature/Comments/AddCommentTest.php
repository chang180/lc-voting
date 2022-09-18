<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Comment;
use App\Http\Livewire\AddComment;
use App\Notifications\CommentAdded;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_comment_livewire_component_renders()
    {
        $idea = Idea::factory()->create();

        $response  = $this->get(route('idea.show', $idea));

        $response->assertSuccessful();
        $response->assertSeeLivewire('add-comment');
    }

    /** @test */
    public function add_comment_form_render_when_user_is_logged_in()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $response  = $this->actingAs($user)->get(route('idea.show', $idea));

        $response->assertSee('Describe your idea');
    }

    /** @test */
    public function add_comment_form_does_not_render_when_user_is_logged_out()
    {
        $idea = Idea::factory()->create();

        $response  = $this->get(route('idea.show', $idea));

        $response->assertSee('Please login to create an idea.');
    }

    /** @test */
    public function add_comment_form_validation_works()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        Notification::fake();

        Notification::assertNothingSent();

        Livewire::actingAs($user)
            ->test(AddComment::class, [
                'idea' => $idea
            ])
            ->set('comment', '')
            ->call('addComment')
            ->assertHasErrors(['comment' => 'required'])
            ->set('comment', 'Tes')
            ->call('addComment')
            ->assertHasErrors(['comment' => 'min'])
            ->set('comment', 'Test Comment')
            ->call('addComment')
            ->assertSuccessful();

        Notification::assertSentTo(
            [$idea->user], CommentAdded::class
        );
    }

    /** @test */
    public function add_comment_form_works()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(AddComment::class, [
                'idea' => $idea
            ])
            ->set('comment', 'Test Comment')
            ->call('addComment')
            ->assertSuccessful()
            ->assertEmitted('commentWasAdded');

        $this->assertEquals(1, $idea->comments()->count());

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'status_id' => 1,
            'body' => 'Test Comment'
        ]);
    }

    /** @test */
    public function comments_pagination_works()
    {

        $idea = Idea::factory()->create();

        $commentOne = Comment::factory()->create([
            'idea_id' => $idea->id,
        ]);

        Comment::factory($commentOne->getPerPage())->create([
            'idea_id' => $idea->id,
        ]);

        $response = $this->get(route('idea.show', $idea));

        $response->assertSee($commentOne->body);
        $response->assertDontSee(Comment::find(Comment::count())->body);

        $response = $this->get(route('idea.show', [
            'idea' => $idea,
            'page' => 2,
        ]));


        $response->assertDontSee($commentOne->body);
        $response->assertSee(Comment::find(Comment::count())->body);
    }
}
