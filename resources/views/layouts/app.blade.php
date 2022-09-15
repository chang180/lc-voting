<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laracasts Voting') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <livewire:styles />
    <script defer src="{{ mix('js/app.js') }}"></script>
</head>

<body class="font-sans text-sm text-gray-900 bg-gray-background">
    <div class="hidden bg-red"></div>
    <div class="hidden bg-yellow"></div>
    <div class="hidden bg-purple"></div>
    <div class="hidden bg-green"></div>
    <div class="hidden text-blue"></div>
    <header class="flex flex-col items-center justify-between px-8 py-4 md:flex-row">
        <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="logo"></a>
        <div class="flex items-center mt-2 md:mt-0">
            @if (Route::has('login'))
                <div class="px-6 py-4">
                    @auth
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>

                            <div x-data="{
                                isOpen: false,
                            }" class="relative">
                                <button @click="isOpen = ! isOpen">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
                                        <path fill-rule="evenodd"
                                            d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div
                                        class="absolute flex items-center justify-center w-6 h-6 text-white border-2 rounded-full -top-1 -right-1 bg-red text-xxs">
                                        9</div>
                                </button>
                                <ul class="absolute z-10 overflow-y-auto text-sm text-left text-gray-700 bg-white -right-28 md:-right-12 w-76 md:w-96 shadow-dialog max-h-128 rounded-xl"
                                    x-cloak x-show.transition.origin.top="isOpen" @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false">
                                    <li><a href="#"
                                            class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                                            <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp"
                                                class="w-10 h-10 rounded-xl" alt="avatar">
                                            <div class="ml-4">
                                                <div class="line-clamp-6">
                                                    <span class="font-semibold">somebody</span>
                                                    commented on
                                                    <span class="font-semibold">This is my idea.</span>
                                                    :
                                                    <span>
                                                        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eius iste
                                                        molestiae magni modi, ipsum, vitae rerum ex a quod praesentium sit
                                                        necessitatibus odit possimus velit voluptates accusantium at quas
                                                        officia? Dolore voluptatum nemo iusto tempore dolorum eveniet
                                                        dignissimos perspiciatis provident?"
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">1 hour ago</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><a href="#"
                                            class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                                            <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp"
                                                class="w-10 h-10 rounded-xl" alt="avatar">
                                            <div class="ml-4">
                                                <div>
                                                    <span class="font-semibold">somebody</span>
                                                    commented on
                                                    <span class="font-semibold">This is my idea.</span>
                                                    :
                                                    <span>
                                                        "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                        iure? Voluptates id deserunt fuga. Illum id veritatis quae error
                                                        rem?"
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">1 hour ago</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><a href="#"
                                            class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                                            <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp"
                                                class="w-10 h-10 rounded-xl" alt="avatar">
                                            <div class="ml-4">
                                                <div>
                                                    <span class="font-semibold">somebody</span>
                                                    commented on
                                                    <span class="font-semibold">This is my idea.</span>
                                                    :
                                                    <span>
                                                        "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                        iure? Voluptates id deserunt fuga. Illum id veritatis quae error
                                                        rem?"
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">1 hour ago</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><a href="#"
                                            class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                                            <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp"
                                                class="w-10 h-10 rounded-xl" alt="avatar">
                                            <div class="ml-4">
                                                <div>
                                                    <span class="font-semibold">somebody</span>
                                                    commented on
                                                    <span class="font-semibold">This is my idea.</span>
                                                    :
                                                    <span>
                                                        "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                        iure? Voluptates id deserunt fuga. Illum id veritatis quae error
                                                        rem?"
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">1 hour ago</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><a href="#"
                                            class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100">
                                            <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp"
                                                class="w-10 h-10 rounded-xl" alt="avatar">
                                            <div class="ml-4">
                                                <div>
                                                    <span class="font-semibold">somebody</span>
                                                    commented on
                                                    <span class="font-semibold">This is my idea.</span>
                                                    :
                                                    <span>
                                                        "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                        iure? Voluptates id deserunt fuga. Illum id veritatis quae error
                                                        rem?"
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">1 hour ago</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="text-center border-t border-gray-300">
                                        <button
                                            class="block w-full px-5 py-4 font-semibold transition duration-150 ease-in hover:bg-gray-100">
                                            Mark all as read
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline dark:text-gray-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="ml-4 text-sm text-gray-700 underline dark:text-gray-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <a href="#">
                <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp" alt="avatar"
                    class="w-10 h-10 rounded-full">
            </a>
        </div>
    </header>
    <main class="container flex flex-col mx-auto max-w-custom md:flex-row">
        <div class="mx-auto w-70 md:mx-0 md:mr-5">
            <div class="mt-16 bg-white border-2 md:sticky md:top-8 border-blue rounded-xl"
                style="
                border-image-source: linear-gradient(to bottom, rgba(50,138,241,0.22),rgba(99,123,255,0));
                border-image-slice:1;
                background-image: linear-gradient(to bottom, #ffffff,#ffffff),linear-gradient(to bottom, rgba(50,138,241,0.22),rgba(99,123,255,0)));
                background-origin:border-box;
                background-clip:content-box,border-box;
                ">
                <div class="px-6 py-2 pt-6 text-center">
                    <h3 class="text-base font-semibold">Add an idea</h3>
                    <p class="mt-4 text-xs">
                        @auth
                            Let us know what you would lik and we'll take a look up!!
                        @else
                            Please login to create an idea.
                        @endauth
                    </p>
                </div>
                @auth
                    <livewire:create-idea />
                @else
                    <div class="my-6 text-center">
                        <a href="{{ route('login') }}"
                            class="justify-center inline-block w-1/2 px-6 py-3 text-xs font-semibold text-white transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="justify-center inline-block w-1/2 px-6 py-3 mt-4 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400">
                            Sign Up
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="w-full px-2 md:px-0 md:w-175">
            <livewire:status-filters />

            <div class="mt-8">
                {{ $slot }}
            </div>
        </div>
    </main>

    @if (session('success_message'))
        <x-notification-success :redirect="true" message-to-display="{{ session('success_message') }}" />
    @endif

    <livewire:scripts />
</body>

</html>
