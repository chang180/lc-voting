<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\EditComment;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\EditIdea;
use App\Http\Livewire\IdeaComment;
use App\Http\Livewire\IdeaShow;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class EditCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_edit_comment_livewire_component_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertSeeLivewire('edit-comment');
    }

    /** @test */
    public function does_not_show_edit_comment_livewire_component_when_user_does_not_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertDontSeeLivewire('edit-comment');
    }

    /** @test */
    public function edit_comment_is_set_correctly_when_user_clicks_it_from_menu()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class,)
            ->call('setEditComment', $comment->id)
            ->assertSet('body', $comment->body)
            ->assertEmitted('editCommentWasSet');
    }

    /** @test */
    public function edit_comment_form_validation_works()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class,)
            ->call('setEditComment', $comment->id)
            ->set('body', '')
            ->call('updateComment')
            ->assertHasErrors(['body' => 'required'])
            ->set('body', '2c')
            ->call('updateComment')
            ->assertHasErrors(['body' => 'min']);
    }

    /** @test */
    public function editing_a_comment_works_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class,)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is a new comment')
            ->call('updateComment')
            ->assertEmitted('commentWasUpdated');

        $this->assertEquals('This is a new comment', Comment::first()->body);
    }

    /** @test */
    public function editing_a_comment_does_not_work_when_user_does_not_have_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is a new comment')
            ->call('updateComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function editing_a_comment_shows_on_menu_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $idea->user_id,
            ])
            ->assertSee('Edit');
    }

    /** @test */
    public function editing_a_comment_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $idea->user_id,
            ])
            ->assertDontSee('Edit');
    }
}
