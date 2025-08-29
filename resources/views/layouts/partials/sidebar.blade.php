{{-- Sidebar moderne avec thème orangé subtil --}}
@auth
@php
    $user = Auth::user();
    $currentRoute = request()->route()->getName();
    $isAdminArea = str_starts_with($currentRoute, 'bo.') || str_starts_with($currentRoute, 'fo.');
    
    // Navigation simplifiée selon le rôle - JUSTE L'ESSENTIEL
    $navigation = [];
    
    if ($user->hasRole('admin')) {
        $navigation = [
            [
                'title' => __('ui.nav.dashboard'),
                'route' => 'bo.dashboard',
                'icon' => 'chart-bar',
                'active' => $currentRoute === 'bo.dashboard'
            ],
            [
                'title' => __('ui.nav.applications'),
                'route' => 'bo.applications.index',
                'icon' => 'document-text',
                'active' => str_contains($currentRoute, 'applications'),
                'badge' => $sidebarData['applications_count'] ?? 0
            ],
            [
                'title' => __('ui.nav.franchisees'),
                'route' => 'bo.franchisees.index',
                'icon' => 'users',
                'active' => str_contains($currentRoute, 'franchisees')
            ],
            [
                'title' => __('ui.nav.trucks'),
                'route' => 'bo.trucks.index',
                'icon' => 'truck',
                'active' => str_contains($currentRoute, 'trucks')
            ],
            [
                'title' => __('ui.nav.stock_items'),
                'route' => 'bo.stock-items.index',
                'icon' => 'cube',
                'active' => str_contains($currentRoute, 'stock-items')
            ],
            [
                'title' => __('ui.nav.purchase_orders'),
                'route' => 'bo.purchase-orders.index',
                'icon' => 'shopping-bag',
                'active' => str_contains($currentRoute, 'purchase-orders')
            ]
        ];
    } elseif ($user->hasRole('warehouse')) {
        $navigation = [
            [
                'title' => __('ui.nav.dashboard'),
                'route' => 'bo.dashboard',
                'icon' => 'chart-bar',
                'active' => $currentRoute === 'bo.dashboard'
            ],
            [
                'title' => __('ui.nav.stock_items'),
                'route' => 'bo.stock-items.index',
                'icon' => 'cube',
                'active' => str_contains($currentRoute, 'stock-items')
            ],
            [
                'title' => __('ui.nav.purchase_orders'),
                'route' => 'bo.purchase-orders.index',
                'icon' => 'shopping-bag',
                'active' => str_contains($currentRoute, 'purchase-orders')
            ]
        ];
    } elseif ($user->hasRole('fleet') || $user->hasRole('tech')) {
        $navigation = [
            [
                'title' => __('ui.nav.dashboard'),
                'route' => 'bo.dashboard',
                'icon' => 'chart-bar',
                'active' => $currentRoute === 'bo.dashboard'
            ],
            [
                'title' => __('ui.nav.trucks'),
                'route' => 'bo.trucks.index',
                'icon' => 'truck',
                'active' => str_contains($currentRoute, 'trucks')
            ]
        ];
    } elseif ($user->hasRole('franchisee')) {
        $navigation = [
            [
                'title' => __('ui.nav.dashboard'),
                'route' => 'fo.dashboard',
                'icon' => 'chart-bar',
                'active' => $currentRoute === 'fo.dashboard'
            ],
            [
                'title' => __('ui.nav.fo_sales'),
                'route' => 'fo.sales.index',
                'icon' => 'currency-dollar',
                'active' => str_contains($currentRoute, 'fo.sales')
            ]
        ];
    }
@endphp

@if($isAdminArea)
{{-- Desktop Sidebar - Position fixe sans superposition --}}
<div class="hidden lg:fixed lg:top-16 lg:bottom-16 lg:left-0 lg:z-40 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col overflow-y-auto bg-white border-r border-orange-100 shadow-sm">
        {{-- Brand mini avec thème orange --}}
        <div class="flex h-12 shrink-0 items-center justify-center border-b border-orange-50 bg-gradient-to-r from-orange-50 to-amber-50">
            <div class="flex items-center gap-x-2">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm">
                    <span class="text-sm font-bold text-white">DC</span>
                </div>
                <span class="text-sm font-semibold text-orange-800">DrivnCook</span>
            </div>
        </div>

        {{-- Navigation principale --}}
        <nav class="flex-1 p-4">
            <ul class="space-y-1">
                @foreach($navigation as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ 
                               $item['active'] 
                                   ? 'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 shadow-sm border border-orange-200' 
                                   : 'text-gray-700 hover:bg-orange-50 hover:text-orange-700' 
                           }}">
                            @include('components.icons.' . $item['icon'], [
                                'class' => 'h-5 w-5 shrink-0 transition-colors ' . (
                                    $item['active'] ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500'
                                )
                            ])
                            <span>{{ $item['title'] }}</span>
                            @if(isset($item['badge']) && $item['badge'] > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium bg-orange-500 text-white rounded-full min-w-[20px] h-5">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- User info compact en bas --}}
        <div class="border-t border-orange-100 p-4">
            <div class="flex items-center gap-x-3">
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-orange-600 truncate">{{ $user->getRoleNames()->first() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Sidebar --}}
<div class="lg:hidden">
    {{-- Backdrop --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-50 bg-gray-900/60" 
         @click="sidebarOpen = false"
         style="display: none;"></div>

    {{-- Mobile panel --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-white shadow-xl"
         style="display: none;">
        
        {{-- Mobile header --}}
        <div class="flex h-16 items-center justify-between px-4 border-b border-orange-100 bg-gradient-to-r from-orange-50 to-amber-50">
            <div class="flex items-center gap-x-2">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center">
                    <span class="text-sm font-bold text-white">DC</span>
                </div>
                <span class="text-sm font-semibold text-orange-800">Menu</span>
            </div>
            <button type="button" 
                    class="text-gray-400 hover:text-orange-600 transition-colors" 
                    @click="sidebarOpen = false">
                <span class="sr-only">Fermer</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile navigation --}}
        <nav class="p-4">
            <ul class="space-y-1">
                @foreach($navigation as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ 
                               $item['active'] 
                                   ? 'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800' 
                                   : 'text-gray-700 hover:bg-orange-50 hover:text-orange-700' 
                           }}"
                           @click="sidebarOpen = false">
                            @include('components.icons.' . $item['icon'], [
                                'class' => 'h-5 w-5 shrink-0 ' . (
                                    $item['active'] ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500'
                                )
                            ])
                            {{ $item['title'] }}
                            @if(isset($item['badge']) && $item['badge'] > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium bg-orange-500 text-white rounded-full">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Mobile user info --}}
        <div class="border-t border-orange-100 p-4 mt-auto">
            <div class="flex items-center gap-x-3">
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-orange-600 truncate">{{ $user->getRoleNames()->first() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endauth
