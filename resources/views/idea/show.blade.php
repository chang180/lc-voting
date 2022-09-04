<x-app-layout>
    <div>
        <a href="{{ $backUrl }}" class="flex item-center font-semibold hover:underline">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="ml-2">All ideas (or back to chosen category with filters)</span>
        </a>
    </div>

    <livewire:idea-show :idea='$idea' :votesCount="$votesCount" />

    @can('update', $idea)
        <livewire:edit-idea :idea='$idea' />
    @endcan

    <div class="comments-container relative space-y-6 my-8 md:ml-22 mt-1 pt-4">
        @for ($i=0; $i < 3; $i++)
        <div class="comment-container relative bg-white rounded-xl flex mt-4">
            <div class="flex flex-col md:flex-row flex-1 px-4 py-6">
                <div class="mx-4 flex-none">
                    <a href="#">
                        <img src="https://source.unsplash.com/200x200/?face&corp=face&v=2" alt="avatar"
                            class="w-14 h-14 rounded-xl">
                    </a>
                </div>
                <div class="mx-2 md:mx-4 w-full">
                    {{-- <h4 class="text-xl font-semibold">
                        <a href="#" class="hover:underline">A random title</a>
                    </h4> --}}
                    <div class="text-gray-600 mt-3 line-clamp-3">
                        111 Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                    </div>
                    <div class="flex md:items-center justify-between mt-6">
                        <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                            <div class="font-bold text-gray-900">John Smith</div>
                            <div class="">&bull;</div>
                            <div>10 hours ago</div>
                        </div>
                        <div x-data="{ isOpen: false }" class="flex items-center space-x-2">
                            <div class="relative">
                                <button @click="isOpen = true"
                                    class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-three-dots" viewBox="0 0 24 24">
                                        <path style="color:rgba(163,163,163,.5)"
                                            d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                    </svg>
                                </button>
                                <ul x-cloak x-show="isOpen" x-transition.origin.top.left @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                    class="absolute z-20 w-44 text-left font-semibold bg-white shadow-dialog rounded-xl py-3 md:ml-8 top-8 md:top-6 right-0 md:left-0">
                                    <li><a href="#"
                                            class="hover:bg-gray-100 px-5 py-3 block transition duration-150 ease-in">Mark
                                            as spam</a></li>
                                    <li><a href="#"
                                            class="hover:bg-gray-100 px-5 py-3 block transition duration-150 ease-in">Delete
                                            Comment</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end comment-container -->
        @endfor
    </div><!-- end comments-container -->
</x-app-layout>
