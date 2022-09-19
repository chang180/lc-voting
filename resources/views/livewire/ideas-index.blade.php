<div>
    <!-- filters -->
    <div class="flex flex-col space-y-3 filters md:flex-row md:space-y-0 md:space-x-6">
        <div class="w-full md:w-1/3">
            <select wire:model="category" name="category" id="category" class="w-full px-4 py-2 border-none rounded-xl">
                <option value="All Categories">Category All</option>
                @foreach ($categories as $category)
                    <option name="category" value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <select wire:model="filter" name="other_filters" id="other_filters"
                class="w-full px-4 py-2 border-none rounded-xl">
                <option value="No Filter">No Filter</option>`
                <option value="Top Voted">Top Voted</option>
                <option value="My Ideas">My Ideas</option>
                @admin
                <option value="Spam Ideas">Spam Ideas</option>
                <option value="Spam Comments">Spam Comments</option>
                @endadmin
            </select>
        </div>
        <div class="relative w-full md:w-2/3">
            <input wire:model="search" type="search" placeholder="Find an idea"
                class="w-full px-4 py-2 pl-8 placeholder-gray-900 bg-white border-none rounded-xl" name=""
                id="">
            <div class="absolute top-0 flex items-center h-full ml-2">
                <svg class="w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>
    <!-- end filters -->
    <div class="my-6 space-y-6 ideas-container">
        @forelse ($ideas as $idea)
            <livewire:idea-index :key="$idea->id" :idea="$idea" :votesCount="$idea->votes_count" />
        @empty
            <div class="flex flex-col items-center justify-center mt-12">
                <img src="{{ asset('img/no-ideas.svg') }}" alt="No Ideas" class="mx-auto mix-blend-luminosity">
                <div class="text-xl font-bold text-center text-gray-400">
                    <p>No ideas was found ...</p>
                </div>
            </div>
        @endforelse
    </div><!-- end ideas-container -->
    <div class="my-8">
        {{ $ideas->links() }}
    </div>
</div>
