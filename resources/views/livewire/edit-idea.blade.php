<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!--
      Background backdrop, show/hide based on modal state.
  
      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->

    <div class="flex min-h-screen items-end justify-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <!--
          Modal panel, show/hide based on modal state.
  
          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
        <div
            class="modal transform overflow-hidden rounded-tl-xl rounded-tr-xl bg-white transition-all py-4 sm:w-full sm:max-w-lg">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="text-gray-400 hover:text-gray-500">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-center text-lg font-medium text-gray-900">Edit Idea</h3>
                <p class="text-xs text-center leading-5 text-gray-500 px-6 mt-4">You have one hour to edit your idea
                    from the time you created it.</p>

                <form wire:submit.prevent='createIdea' action="#" method="post" class="space-y-4 px-4 py-6">
                    <div>
                        <input wire:model.defer="title" type="text" name="" id=""
                            class="w-full text-sm border-none bg-gray-100 rounded-xl placeholder-gray-900 px-4 py-2"
                            placeholder="Your idea" required>
                        @error('title')
                            <p class="text-red text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <select wire:model.defer="category" name="category_add" id="category_add"
                            class="w-full bg-gray-100 text-sm rounded-xl border-none px-4 py-2">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <textarea wire:model.defer="description" name="idea" id="idea" cols="30" rows="4"
                            class="w-full bg-gray-100 text-sm rounded-xl placeholder-gray-900 border-none px-4 py-2"
                            placeholder="Describe your idea" required></textarea>
                        @error('description')
                            <p class="text-red text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="flex items-center jstify-between space-x-3">
                        <button type="button"
                            class="flex items-center justify-center w-1/2 h-11 text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3">
                            <svg class="h-4 w-4 text-gray-600 transform -rotate-45" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="ml-1">Attach</span>
                        </button>
                        <button type="submit"
                            class="flex items-center justify-center w-1/2 h-11 text-xs bg-blue text-white font-semibold rounded-xl border border-blue hover:bg-blue-hover transition duration-150 ease-in px-6 py-3">
                            <span class="ml-1">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
