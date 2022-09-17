<div x-data="{
    isOpen: false,
}" class="relative">
    <button
        @click="
        isOpen = ! isOpen
        if(isOpen){
            Livewire.emit('getNotifications')
        }
    ">
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
            <path fill-rule="evenodd"
                d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                clip-rule="evenodd" />
        </svg>
        @if ($notificationsCount)
            <div
                class="absolute flex items-center justify-center w-6 h-6 text-white border-2 rounded-full -top-2 -right-2 bg-red text-xxs">
                {{ $notificationsCount }}</div>
        @endif
    </button>
    <ul class="absolute z-10 overflow-y-auto text-sm text-left text-gray-700 bg-white -right-28 md:-right-12 w-76 md:w-96 shadow-dialog max-h-128 rounded-xl"
        x-cloak x-show.transition.origin.top="isOpen" @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false">
        @if ($notifications->isNotEmpty() && !$isLoading)
            @foreach ($notifications as $notification)
                <li><a 
                        href="{{ route('idea.show', $notification->data['idea_slug']) }}"
                        @click.prevent="
                            isOpen = false
                        "
                        wire:click.prevent="
                            markAsRead('{{ $notification->id }}')
                        "
                        class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                        <img src="{{ $notification->data['user_avatar'] }}" alt="avatar" class="w-10 h-10 rounded-xl"
                            alt="avatar">
                        <div class="ml-4">
                            <div class="line-clamp-6">
                                <span class="font-semibold">{{ $notification->data['user_name'] }}</span>
                                commented on
                                <span class="font-semibold">{{ $notification->data['idea_title'] }}</span>
                                :
                                <span>
                                    {{ $notification->data['comment_body'] }}
                                </span>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
            <li class="text-center border-t border-gray-300">
                <button 
                    @click="isOpen = false"
                    wire:click="markAllAsRead"
                    class="block w-full px-5 py-4 font-semibold transition duration-150 ease-in hover:bg-gray-100">
                    Mark all as read
                </button>
            </li>
        @elseif ($isLoading)
            @foreach (range(1, 3) as $item)
                <li class="flex items-center px-5 py-4 transition duration-150 ease-in animate-pulse">
                    <div class="w-10 h-10 bg-gray-200 rounded-xl"></div>
                    <div class="flex-1 ml-4 space-y-2">
                        <div class="w-full h-4 bg-gray-200 rounded"></div>
                        <div class="w-full h-4 bg-gray-200 rounded"></div>
                        <div class="w-1/2 h-4 bg-gray-200 rounded"></div>
                    </div>
                </li>
            @endforeach
        @else
            <li class="w-40 py-6 mx-auto">
                <img src="{{ asset('img/no-notifications.svg') }}" alt="No notifications yet"
                    style="mix-blend-mode: luminosity">
                <div class="mt-6 font-bold text-center text-gray-400">No new notifications</div>
            </li>
        @endif
    </ul>
</div>
