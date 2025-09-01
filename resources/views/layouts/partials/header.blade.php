<header class="border-b bg-white shadow-sm z-30 h-16 sticky top-0">
    <div class="w-full px-6">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo et titre -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <!-- Logo placeholder -->
                    <div class="h-9 w-9 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm">DC</span>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                </a>

                <!-- Bouton burger mobile -->
                @auth
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-150"
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
            <div class="flex items-center gap-2">
                <!-- Public Nav -->
                <a href="{{ route('public.applications.create') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 text-sm font-medium transition-colors duration-150">
                    {{ __('ui.nav.apply') }}
                </a>
                <a href="{{ route('public.franchise') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 text-sm font-medium transition-colors duration-150">
                    {{ __('ui.nav.franchise_info') }}
                </a>

                @guest
                    <a href="{{ route('login') }}" class="ml-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150">
                        {{ __('ui.login') }}
                    </a>
                @else
                    <!-- Menu utilisateur -->
                    <div class="relative ml-2" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-2 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-2 rounded-md transition-colors duration-150"
                        >
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100"
                        >
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('ui.profile') }}
                            </a>
                            
                            <!-- Language switcher -->
                            <hr class="border-gray-100">
                            <div class="px-4 py-2">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('ui.language') }}</span>
                                <div class="mt-1 flex gap-1">
                                    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="locale" value="fr">
                                        <button type="submit" class="px-2 py-1 text-xs rounded {{ app()->getLocale() === 'fr' ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                            FR
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit" class="px-2 py-1 text-xs rounded {{ app()->getLocale() === 'en' ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                            EN
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
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
