<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Livewire\EditIdea;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\DeleteIdea;
use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\MarkIdeaAsSpam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_idea_as_spam_livewire_component_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertSeeLivewire('mark-idea-as-spam');
    }

    /** @test */
    public function does_not_show_mark_idea_as_spam_livewire_component_when_user_does_not_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertSuccessful()
            ->assertDontSeeLivewire('mark-idea-as-spam');
    }

    /** @test */
    public function marking_an_idea_as_spam_works_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(MarkIdeaAsSpam::class, [
                'idea' => $idea,
            ])
            ->call('markAsSpam')
            ->assertEmitted('ideaWasMarkedAsSpam');

        $this->assertEquals(1, $idea->fresh()->spam_reports);
    }

    /** @test */
    public function marking_an_idea_as_spam_does_not_work_when_user_does_not_have_authorization()
    {

        $idea = Idea::factory()->create();

        Livewire::test(MarkIdeaAsSpam::class, [
            'idea' => $idea,
        ])
            ->call('markAsSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_an_idea_as_spam_shows_on_menu_when_user_has_authorization()
    {
        User::factory()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(MarkIdeaAsSpam::class, [
                'idea' => $idea,
                'votesCount' => 4,
            ])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_ideas_index_page_if_logged_in_as_admin()
    {
        User::factory()->admin()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, [
                'idea' => $idea,
                'votesCount' => 4,
            ])
            ->assertSee('Spam Reports: 3');
    }

    /** @test */
    public function spam_reports_count_shows_on_ideas_show_page_if_logged_in_as_admin()
    {
        User::factory()->admin()->create();
        $user = User::find(1);

        $idea = Idea::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class, [
                'idea' => $idea,
                'votesCount' => 4,
            ])
            ->assertSee('Spam Reports: 3');
    }
}
