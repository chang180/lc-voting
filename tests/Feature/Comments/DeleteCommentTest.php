<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Livewire\EditIdea;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\EditComment;
use App\Http\Livewire\IdeaComment;
use App\Http\Livewire\DeleteComment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_delete_comment_livewire_component_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertSeeLivewire('delete-comment');
    }

    /** @test */
    public function does_not_show_delete_comment_livewire_component_when_user_does_not_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertDontSeeLivewire('delete-comment');
    }

    /** @test */
    public function delete_comment_is_set_correctly_when_user_clicks_it_from_menu()
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
            ->test(DeleteComment::class,)
            ->call('setDeleteComment', $comment->id)
            ->assertEmitted('deleteCommentWasSet');
    }

    /** @test */
    public function deleting_a_comment_works_when_user_has_authorization()
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
            ->test(DeleteComment::class,)
            ->call('setDeleteComment', $comment->id)
            ->call('deleteComment')
            ->assertEmitted('commentWasDeleted');

        $this->assertEquals(0, Comment::count());
    }

    /** @test */
    public function deleting_a_comment_does_not_work_when_user_does_not_have_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(DeleteComment::class)
            ->call('setDeleteComment', $comment->id)
            ->call('deleteComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function deleting_a_comment_shows_on_menu_when_user_has_authorization()
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
            ->assertSee('Delete');
    }

    /** @test */
    public function deleting_a_comment_does_not_show_on_menu_when_user_does_not_have_authorization()
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
            ->assertDontSee('Delete');
    }
}
