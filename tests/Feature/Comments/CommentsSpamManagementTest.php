<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Comment;
use Illuminate\Http\Response;
use App\Http\Livewire\IdeaComment;
use App\Http\Livewire\MarkCommentAsSpam;
use App\Http\Livewire\MarkCommentAsNotSpam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsSpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_comment_as_spam_livewire_component_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function does_not_show_mark_comment_as_spam_livewire_component_when_user_does_not_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        $this->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertDontSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_works_when_user_has_authorization()
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
            ->test(MarkCommentAsSpam::class)
            ->call('setMarkCommentAsSpam', $comment->id)
            ->call('markAsSpam')
            ->assertEmitted('commentWasMarkedAsSpam');

        $this->assertEquals(1, $comment->fresh()->spam_reports);
    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::test(MarkCommentAsSpam::class)
            ->call('setMarkCommentAsSpam', $comment->id)
            ->call('markAsSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_a_comment_as_spam_shows_on_menu_when_user_has_authorization()
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
            ->assertSee('Mark as');

    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::test(IdeaComment::class, [
            'comment' => $comment,
            'ideaUserId' => $idea->user_id,
        ])
            ->assertDontSee('Mark as');
    }

    /** @test */
    public function shows_mark_comment_as_not_spam_livewire_component_when_user_has_authorization()
    {
        User::factory()->admin()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function does_not_show_mark_comment_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
    User::factory()->create();
    $user = User::find(1);

    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'This is a comment',
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSuccessful()
        ->assertDontSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function marking_a_comment_as_not_spam_works_when_user_has_authorization()
    {
        User::factory()->admin()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkCommentAsNotSpam', $comment->id)
            ->call('markAsNotSpam')
            ->assertEmitted('commentWasMarkedAsNotSpam');

        $this->assertEquals(0, $comment->fresh()->spam_reports);
    }

    /** @test */
    public function marking_a_comment_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {
        User::factory()->create();
        $user = User::find(1);
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is a comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkCommentAsNotSpam', $comment->id)
            ->call('markAsNotSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_a_comment_as_not_spam_does_not_shows_on_menu_when_user_does_not_have_authorization()
    {
        User::factory()->admin()->create();
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
            ->assertDontSee('Not Spam');
    }

}
