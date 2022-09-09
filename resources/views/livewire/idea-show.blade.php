<div>
    <div class="flex mt-4 bg-white idea-container rounded-xl">
        <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
            <div class="flex-none mx-2">
                <a href="#">
                    <img src="{{ $idea->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
                </a>
            </div>
            <div class="w-full mx-2 md:mx-4">
                <h4 class="mt-2 text-xl font-semibold md:mt-0">
                    {{ $idea->title }}
                </h4>
                <div class="mt-3 text-gray-600">
                    @admin
                        @if ($idea->spam_reports > 0)
                            <div class="text-red mb-w">Spam Reports: {{ $idea->spam_reports }}</div>
                        @endif
                    @endadmin
                    {{ $idea->description }}
                </div>
                <div class="flex flex-col justify-between mt-6 md:flex-row md:items-center">
                    <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                        <div class="text-gray-900">{{ $idea->user->name }}</div>
                        <div class="">&bull;</div>
                        <div>{{ $idea->created_at->diffForHumans() }}</div>
                        <div>&bull;</div>
                        <div>{{ $idea->category->name }}</div>
                        <div>&bull;</div>
                        <div class="text-gray-900">{{ $idea->comments_count }} Comments</div>
                    </div>
                    <div x-data="{ isOpen: false }" class="flex items-center mt-4 space-x-2 md:mt-0">
                        <div
                            class="{{ $idea->status->classes }} text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 px-4 py-2">
                            {{ $idea->status->name }}</div>
                        @auth
                            <div class="relative">
                                <button @click="isOpen = true"
                                    class="relative px-3 py-2 transition duration-150 ease-in bg-gray-100 border rounded-full hover:bg-gray-200 h-7">
                                    <svg width="24" height="24" fill="currentColor" class="bi bi-three-dots"
                                        viewBox="0 0 24 24">
                                        <path style="color:rgba(163,163,163,.5)"
                                            d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                    </svg>
                                </button>
                                <ul x-cloak x-show="isOpen" x-transition.origin.top.left @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                    class="absolute right-0 z-10 py-3 font-semibold text-left bg-white w-44 shadow-dialog rounded-xl md:ml-8 top-8 md:top-6 md:left-0">
                                    @can('update', $idea)
                                        <li><a href="#"
                                                @click.prevent="
                                            isOpen = false
                                            $dispatch('custom-show-edit-modal')
                                        "
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Edit
                                                Idea</a></li>
                                    @endcan
                                    @can('delete', $idea)
                                        <li><a href="#"
                                                @click.prevent="
                                            isOpen = false
                                            $dispatch('custom-show-delete-modal')
                                        "
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Delete
                                                Idea</a></li>
                                    @endcan
                                    <li><a href="#"
                                            @click.prevent="
                                            isOpen = false
                                            $dispatch('custom-show-mark-idea-as-spam-modal')
                                        "
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Mark
                                            as Spam</a></li>
                                    <li><a href="#"
                                            @click.prevent="
                                            isOpen = false
                                            $dispatch('custom-show-mark-idea-as-not-spam-modal')
                                        "
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Not
                                            Spam</a></li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                    <div class="flex items-center mt-4 md:hidden md:mt-9">
                        <div class="h-10 px-4 py-2 pr-8 text-center bg-gray-100 rounded-xl">
                            <div
                                class="text-sm font-bold leading-none @if ($hasVoted) text-blue @endif">
                                {{ $votesCount }}</div>
                            <div class="font-semibold leading-none text-gray-400 text-xss">Votes</div>
                        </div>
                        @if ($hasVoted)
                            <button wire:click.prevent="vote"
                                class="w-20 px-4 py-3 -mx-5 font-bold text-white uppercase transition duration-150 ease-in border bg-blue border-blue hover:bg-blue-hover text-xxs rounded-xl">
                                Voted</button>
                        @else
                            <button wire:click.prevent="vote"
                                class="w-20 px-4 py-3 -mx-5 font-bold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 hover:border-gray-400 text-xxs rounded-xl">
                                Vote</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end idea-container -->


    <div class="flex items-center justify-between mt-6 button-container">
        <div class="flex flex-col items-center md:flex-row md:space-x-4 md:ml-6">
            <div x-data="{ isOpen: false }" class="relative">
                <button @click="isOpen = true" type="button"
                    class="flex items-center justify-center px-6 py-3 text-sm font-semibold transition duration-150 ease-in border h-11 w-36 bg-blue rounded-xl border-blue hover:bg-blue-hover">
                    Reply
                </button>
                <div x-cloak x-show="isOpen" x-transition.origin.top.left @click.away="isOpen = false"
                    @keydown.escape.window="isOpen = false"
                    class="absolute z-10 w-64 mt-2 text-sm font-semibold text-left bg-white md:w-104 shadow-dialog rounded-xl">
                    <form action="#" class="px-4 py-6 space-y-4">
                        <div>
                            <textarea name="post_comment" id="post_comment" cols="30" rows="4"
                                class="w-full px-4 py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl"
                                placeholder="Go ahead, don't be shine. Share your thoughts..."></textarea>
                        </div>
                        <div class="flex flex-col items-center md:flex-row md:space-x-3">
                            <button type="button"
                                class="flex items-center justify-center w-full px-6 py-3 text-sm font-semibold transition duration-150 ease-in border h-11 md:w-1/2 bg-blue rounded-xl border-blue hover:bg-blue-hover">
                                Post Comment
                            </button>
                            <button type="button"
                                class="flex items-center justify-center w-full px-6 py-3 mt-2 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 md:w-32 h-11 rounded-xl hover:border-gray-400 md:mt-0">
                                <svg class="w-4 h-4 text-gray-600 transform -rotate-45" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="ml-1">Attach</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @admin
                <livewire:set-status :idea="$idea" />
            @endadmin
        </div>
        <div class="items-center hidden space-x-3 md:flex">
            <div class="px-3 py-2 font-semibold text-center bg-white rounded-xl">
                <div class="text-xl leading-none @if ($hasVoted) text-blue @endif">
                    {{ $votesCount }}
                </div>
                <div class="text-xs leading-none text-gray-400">Votes</div>
            </div>
            @if ($hasVoted)
                <button type="button" wire:click.prevent="vote"
                    class="items-center justify-center w-32 px-6 py-3 text-xs font-semibold text-white uppercase transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover">
                    <span>Voted</span>
                </button>
            @else
                <button type="button" wire:click.prevent="vote"
                    class="items-center justify-center w-32 px-6 py-3 text-xs font-semibold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400">
                    <span>Vote</span>
                </button>
            @endif
        </div>
    </div><!-- end button-container -->
</div>
