<div>
    @if ($comments->isNotEmpty())
        <div class="relative pt-4 my-8 mt-1 space-y-6 comments-container md:ml-22">
            @foreach ($comments as $comment)
                <livewire:idea-comment :key="$comment->id" :comment="$comment" :ideaUserId="$idea->user->id">
            @endforeach

        </div><!-- end comments-container -->
        <div class="my-8 ml-22">
            {{ $comments->onEachSide(1)->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center mt-12">
            <img src="{{ asset('img/no-ideas.svg') }}" alt="No Ideas" class="mix-blend-luminosity">
            <div class="text-xl font-bold text-center text-gray-400">
                <p>No comment yet!</p>
            </div>
        </div>

    @endif
</div>
