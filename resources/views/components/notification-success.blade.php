@props([
    'redirect' => false,
    'messageToDisplay' => '',
])
<div x-cloak x-data="{
    isOpen: false,
    messageToDisplay: '{{ $messageToDisplay }}',
    showNotification(message) {
        this.messageToDisplay = message
        this.isOpen = true
        setTimeout(() => {
            this.isOpen = false
        }, 5000)
    }
}" x-init="
@if($redirect)
$nextTick(() => {
    showNotification(messageToDisplay)
})
@else
Livewire.on('ideaWasUpdated', message => {
    showNotification(message)
})

Livewire.on('ideaWasMarkedAsSpam', message => {
    showNotification(message)
})

Livewire.on('ideaWasMarkedAsNotSpam', message => {
    showNotification(message)
})

Livewire.on('commentWasAdded', message => {
    showNotification(message)
})

Livewire.on('commentWasUpdated', message => {
    showNotification(message)
})

Livewire.on('commentWasDeleted', message => {
    showNotification(message)
})

Livewire.on('commentWasMarkedAsSpam', message => {
    showNotification(message)
})

Livewire.on('commentWasMarkedAsNotSpam', message => {
    showNotification(message)
})

@endif
" x-show="isOpen"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8"
    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
    @keydown.escape.window="isOpen = false"
    class="fixed bottom-0 right-0 z-20 flex justify-between w-full max-w-xs px-4 py-5 mx-2 my-8 bg-white border shadow-lg sm:max-w-sm rounded-xl sm:mx-6">
    <div class="flex items-center">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="ml-2 text-sm font-semibold text-gray-500 sm:text-base" x-text="messageToDisplay"></div>
    </div>
    <button @click="isOpen = false" class="text-gray-400 hover:text_gray-500">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
