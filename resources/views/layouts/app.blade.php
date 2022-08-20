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
        <script defer src="{{ mix('js/app.js') }}"></script>
    </head>
    <body class="font-sans bg-gray-background text-gray-900 text-sm">
        <header class="flex items-center justify-between px-8 py-4">
            <a href="#"><img src="{{ asset('img/logo.svg') }}" alt="logo"></a>
            <div class="flex items-center">
                @if (Route::has('login'))
                <div class="px-6 py-4">
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
    
                        <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif  
                <a href="#">
                    <img src="https://www.gravatar.com/avatar/000000000000000000000000000000?d=mp" 
                    alt="avatar" class="w-10 h-10 rounded-full">
                </a>
            </div>
        </header>
        <main class="container mx-auto max-w-custom flex">
            <div class="w-70 mr-5">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsum accusantium ipsam molestiae sunt! Minus fuga aliquam nostrum maiores est ex debitis, pariatur iste dolore odit. Et inventore omnis magni! Incidunt officiis fugiat reprehenderit iste quos totam culpa, neque labore sequi animi? Neque voluptates exercitationem eligendi non, hic ipsam voluptatem dolor ex recusandae adipisci tempore, quas assumenda temporibus itaque eum distinctio explicabo ipsa quidem molestiae. Commodi labore exercitationem porro veritatis, aliquam, a dolorem aspernatur corporis dolore maxime nihil amet ad vero dolor ut magni necessitatibus doloribus nulla ipsa assumenda praesentium animi deleniti sit? Labore esse omnis ad maiores alias distinctio sapiente!
            </div>
            <div class="w-175">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore minima ab recusandae nam laboriosam aperiam, hic provident vero quo! Accusamus suscipit, qui recusandae distinctio excepturi eius accusantium eveniet, perspiciatis necessitatibus quae quod possimus ipsa aliquam deleniti. Repellendus excepturi temporibus impedit vel, adipisci nisi laboriosam saepe ipsum accusamus maxime fuga eum necessitatibus ducimus, molestiae totam laborum quibusdam aspernatur molestias doloremque. Iure sit dolore labore maiores numquam distinctio laboriosam laudantium, eos sapiente placeat, nisi, repudiandae praesentium aperiam perspiciatis reprehenderit iusto nihil consequuntur magni quasi odit expedita aliquid molestiae incidunt explicabo. Adipisci aperiam perspiciatis id cumque animi? Excepturi, veniam ipsa. Culpa, alias nulla.</div>
        </main>
    </body>
</html>
