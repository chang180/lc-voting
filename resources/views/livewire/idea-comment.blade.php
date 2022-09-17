<div
    id="comment-{{ $comment->id }}"
    class="@if ($comment->is_status_update) is-status-update {{ 'status-'.Str::kebab($comment->status->name) }}@endif relative flex mt-4 transition duration-500 ease-in bg-white comment-container rounded-xl">
    <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
        <div class="flex-none mx-4">
            <a href="#">
                <img src="{{ $comment->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
            </a>
            @if ($comment->user->isAdmin())
                <div class="mt-1 font-bold text-center uppercase text-blue text-xxs">Admin</div>
            @endif
        </div>
        <div class="w-full md:mx-4">
            <div class="text-gray-600">
                @admin
                    @if ($comment->spam_reports > 0)
                        <div class="text-red mb-w">Spam Reports: {{ $comment->spam_reports }}</div>
                    @endif
                @endadmin
                @if ($comment->is_status_update)
                    <h4 class="mb-3 text-xl font-semibold">
                        Status Changed to "{{ $comment->status->name }}"
                    </h4>
                @endif
                <div>
                    {{ $comment->body }}
                </div>
            </div>
            <div class="flex justify-between mt-6 md:items-center">
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                    <div class="@if ($comment->is_status_update) text-blue @endif font-bold text-gray-900">
                        {{ $comment->user->name }}</div>
                    <div class="">&bull;</div>
                    {{-- check if the post is posted by the original poster --}}
                    @if ($comment->user_id === $ideaUserId)
                        <div class="px-3 py-1 bg-gray-100 border rounded-full">OP</div>
                        <div class="">&bull;</div>
                    @endif

                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                @auth
                    <div x-data="{ isOpen: false }" class="flex items-center space-x-2 text-gray-900">
                        <div class="relative">
                            <button @click="isOpen = true"
                                class="relative px-3 py-2 transition duration-150 ease-in bg-gray-100 border rounded-full hover:bg-gray-200 h-7">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-three-dots" viewBox="0 0 24 24">
                                    <path style="color:rgba(163,163,163,.5)"
                                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                </svg>
                            </button>
                            <ul x-cloak x-show="isOpen" x-transition.origin.top.left @click.away="isOpen = false"
                                @keydown.escape.window="isOpen = false"
                                class="absolute right-0 z-20 py-3 font-semibold text-left bg-white w-44 shadow-dialog rounded-xl md:ml-8 top-8 md:top-6 md:left-0">
                                @can('update', $comment)
                                    <li><a href="#"
                                            @click.prevent="
                                                isOpen = false
                                                Livewire.emit('setEditComment', {{ $comment->id }})
                                            "
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Edit
                                            Comment</a></li>
                                @endcan
                                @can('delete', $comment)
                                    <li><a href="#"
                                            @click.prevent="
                                                isOpen = false
                                                Livewire.emit('setDeleteComment', {{ $comment->id }})
                                            "
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Delete
                                            Comment</a></li>
                                @endcan
                                <li><a href="#"
                                        @click.prevent="
                                                isOpen = false
                                                Livewire.emit('setMarkCommentAsSpam', {{ $comment->id }})
                                            "
                                        class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Mark as
                                        Spam</a></li>
                                @admin
                                    <li><a href="#"
                                            @click.prevent="
                                                isOpen = false
                                                Livewire.emit('setMarkCommentAsNotSpam', {{ $comment->id }})
                                            "
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Mark as
                                            Not
                                            Spam</a></li>
                                @endadmin
                            </ul>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div><!-- end comment-container -->
