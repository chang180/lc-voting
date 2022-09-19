<div 
    class="relative" 
    x-data="{ isOpen: false }"
    x-init="
        Livewire.on('statusWasUpdated', () => {
            isOpen = false
        })
        
        Livewire.on('statusWasUpdatedError', () => {
            isOpen = false
        })"
>
    <button @click="isOpen = !isOpen" type="button"
        class="flex items-center justify-center px-6 py-3 mt-2 text-sm font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 w-36 h-11 rounded-xl hover:border-gray-400 md:mt-0">
        <span>Set Status</span>
        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-cloak x-show="isOpen" x-transition.origin.top.left @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false"
        class="absolute z-20 w-64 mt-2 text-sm font-semibold text-left bg-white md:w-76 shadow-dialog rounded-xl">
        <form wire:submit.prevent='setStatus' action="#" class="px-4 py-6 space-y-4">
            <div class="spact-y-2">
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" name="radio-direct" checkedd=""
                            class="bg-gray-200 border-none" value="1">
                        <span class="ml-2">Open</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" name="radio-direct" checkedd=""
                            class="bg-gray-200 border-none text-purple" value="2">
                        <span class="ml-2">Considering</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" name="radio-direct" checkedd=""
                            class="bg-gray-200 border-none text-yellow" value="3">
                        <span class="ml-2">In Progress</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" name="radio-direct" checkedd=""
                            class="bg-gray-200 border-none text-green" value="4">
                        <span class="ml-2">Implemented</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" name="radio-direct" checkedd=""
                            class="bg-gray-200 border-none text-red" value="5">
                        <span class="ml-2">Closed</span>
                    </label>
                </div>
            </div>
            <textarea wire:model="comment" name="update_comment" id="update_comment" cols="30" rows="3"
                class="w-full py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl pyx-4"
                placeholder="Add an update comment (optiOnal)"></textarea>

            <div class="flex items-center space-x-3 jstify-between">
                <button type="button"
                    class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400">
                    <svg class="w-4 h-4 text-gray-600 transform -rotate-45" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span class="ml-1">Attach</span>
                </button>
                <button type="submit"
                    class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold text-white transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover disabled:opacity-50">
                    <span class="ml-1">Update</span>
                </button>
            </div>
            <div>
                <label for="" class="inline-flex items-center font-normal">
                    <input wire:model="notifyAllVoters" type="checkbox" name="notify_voters" id="notify_voters" class="bg-gray-200 rounded">
                    <span class="ml-2">Notify all voters</span>
                </label>
            </div>
        </form>
    </div>
</div>
