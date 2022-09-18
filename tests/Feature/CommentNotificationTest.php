<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Comment;
use App\Http\Livewire\AddComment;
use App\Http\Livewire\CommentNotifications;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class CommentNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function comment_notification_livewire_component_renders_when_user_logged_in()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $response = $this->actingAs($user)->get(route('idea.index'));

        $response->assertSeeLivewire('comment-notifications');
    }

    /** @test */
    public function comment_notification_livewire_component_does_not_render_when_user_not_logged_out()
    {
        $response = $this->get(route('idea.index'));

        $response->assertDontSeeLivewire('comment-notifications');
    }

    /** @test */
    public function notifications_show_for_logged_in_user()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();
        $userBCommenting = User::factory()->create();

        Livewire::actingAs($userACommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the first comment')
            ->call('addComment');

        sleep(1);

        Livewire::actingAs($userBCommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the second comment')
            ->call('addComment');

        DatabaseNotification::first()->update([
            'created_at' => now()->subMinute(),
        ]);

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->assertSeeInOrder([
                'This is the second comment',
                'This is the first comment',
            ])
            ->assertSet('notificationsCount', 2);
    }

    /** @test */
    public function notification_count_greater_than_threshold_shows_for_logged_in_user()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();
        $threshold = CommentNotifications::NOTIFICATION_THRESHOLD;

        foreach (range(1, $threshold + 1) as $value) {
            Livewire::actingAs($userACommenting)
                ->test(AddComment::class, [
                    'idea' => $idea,
                ])
                ->set('comment', 'This is a comment')
                ->call('addComment');
        }

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->assertSet('notificationsCount', $threshold . '+')
            ->assertSee($threshold . '+');
    }

    /** @test */
    public function can_mark_all_notifications_as_read()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();
        $userBCommenting = User::factory()->create();

        Livewire::actingAs($userACommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the first comment')
            ->call('addComment');

        Livewire::actingAs($userBCommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the second comment')
            ->call('addComment');

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->call('markAllAsRead')
            ->assertSet('notificationsCount', 0);

        $this->assertEquals(0, $user->fresh()->unreadNotifications->count());
    }

    /** @test */
    public function can_mark_individual_notification_as_read()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();
        $userBCommenting = User::factory()->create();

        Livewire::actingAs($userACommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the first comment')
            ->call('addComment');

        Livewire::actingAs($userBCommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the second comment')
            ->call('addComment');

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->call('markAsRead', DatabaseNotification::first()->id)
            ->assertRedirect(route('idea.show', [
                'idea' => $idea,
                'page' => 1,
            ]));

        $this->assertEquals(1, $user->fresh()->unreadNotifications->count());
    }

    /** @test */
    public function notificaton_idea_deleted_redirects_to_index_page()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();

        Livewire::actingAs($userACommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the first comment')
            ->call('addComment');

        $idea->delete(); // remember to set model for cascade delete

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->call('markAsRead', DatabaseNotification::first()->id)
            ->assertRedirect(route('idea.index'));

    }

    /** @test */
    public function notification_comment_deleted_reirects_to_index_page()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $userACommenting = User::factory()->create();

        Livewire::actingAs($userACommenting)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'This is the first comment')
            ->call('addComment');

        $idea->comments()->delete();

        Livewire::actingAs($user)
            ->test(CommentNotifications::class)
            ->call('getNotifications')
            ->call('markAsRead', DatabaseNotification::first()->id)
            ->assertRedirect(route('idea.index'));
    }


}
