<header class="border-b bg-white shadow-sm z-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo et titre -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <!-- Logo placeholder -->
                    <div class="h-8 w-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">DC</span>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                </div>

                <!-- Bouton burger mobile -->
                @auth
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                    aria-controls="mobile-sidebar"
                    :aria-expanded="sidebarOpen"
                >
                    <span class="sr-only">{{ __('ui.open_menu') }}</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                @endauth
            </div>

            <!-- Navigation utilisateur -->
            <div class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                        {{ __('ui.login') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            {{ __('ui.register') }}
                        </a>
                    @endif
                @else
                    <!-- Menu utilisateur -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md hover:bg-gray-100"
                        >
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                        >
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('ui.profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('ui.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>
