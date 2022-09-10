<div class="relative flex mt-4 transition duration-500 ease-in bg-white comment-container rounded-xl">
    <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
        <div class="flex-none mx-4">
            <a href="#">
                <img src="{{ $comment->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
            </a>
        </div>
        <div class="w-full md:mx-4">
            <div class="text-gray-600">
                {{ $comment->body }}
            </div>
            <div class="flex justify-between mt-6 md:items-center">
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                    <div class="font-bold text-gray-900">{{ $comment->user->name }}</div>
                    <div class="">&bull;</div>
                    {{-- check if the post is posted by the original poster --}}
                    @if ($comment->user_id === $ideaUserId)
                        <div class="px-3 py-1 bg-gray-100 border rounded-full">OP</div>
                        <div class="">&bull;</div>
                    @endif

                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                <div x-data="{ isOpen: false }" class="flex items-center space-x-2">
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
                            <li><a href="#"
                                    class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Mark
                                    as spam</a></li>
                            <li><a href="#"
                                    class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">Delete
                                    Comment</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end comment-container -->
