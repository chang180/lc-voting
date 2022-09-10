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
                        <div class="text-gray-900">{{ $idea->comments->count() }} Comments</div>
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
            
            <livewire:add-comment :idea="$idea" />

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
