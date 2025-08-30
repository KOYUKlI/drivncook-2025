{{-- Sidebar simple et épurée avec thème orange DrivnCook --}}
@auth
@php
    $user = Auth::user();
    $currentRoute = request()->route()->getName();
    $isAdminArea = str_starts_with($currentRoute, 'bo.') || str_starts_with($currentRoute, 'fo.');
    
    // Navigation simple selon le rôle
    $navigationItems = [];
    
    if ($user->hasRole('admin')) {
        $navigationItems = [
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
                'active' => str_contains($currentRoute, 'applications')
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
                'title' => __('ui.nav.warehouses'),
                'route' => 'bo.warehouses.index',
                'icon' => 'building-storefront',
                'active' => str_contains($currentRoute, 'warehouses')
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
            ],
            [
                'title' => __('ui.nav.reports_monthly'),
                'route' => 'bo.reports.monthly',
                'icon' => 'chart-pie',
                'active' => $currentRoute === 'bo.reports.monthly'
            ]
        ];
    } elseif ($user->hasRole('warehouse')) {
        $navigationItems = [
            [
                'title' => __('ui.nav.dashboard'),
                'route' => 'bo.dashboard',
                'icon' => 'chart-bar',
                'active' => $currentRoute === 'bo.dashboard'
            ],
            [
                'title' => __('ui.nav.warehouses'),
                'route' => 'bo.warehouses.index',
                'icon' => 'building-storefront',
                'active' => str_contains($currentRoute, 'warehouses')
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
    } elseif ($user->hasRole('fleet')) {
        $navigationItems = [
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
    } elseif ($user->hasRole('tech')) {
        $navigationItems = [
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
        $navigationItems = [
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
            ],
            [
                'title' => __('ui.nav.fo_reports'),
                'route' => 'fo.reports.index',
                'icon' => 'chart-pie',
                'active' => str_contains($currentRoute, 'fo.reports')
            ]
        ];
    }
@endphp

@if($isAdminArea)
<!-- Desktop Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col overflow-y-auto bg-gradient-to-b from-orange-600 to-orange-700 shadow-xl">
        <!-- Logo DrivnCook -->
        <div class="flex h-16 shrink-0 items-center px-6">
            <div class="flex items-center gap-x-3">
                <div class="h-10 w-10 bg-white rounded-lg flex items-center justify-center">
                    <span class="text-orange-600 font-bold text-lg">DC</span>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Driv'n Cook</h1>
                    <p class="text-orange-200 text-xs">Administration</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-1 flex-col px-6 py-4">
            <ul role="list" class="space-y-1">
                @foreach($navigationItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium {{ $item['active'] ? 'bg-orange-500 text-white' : 'text-orange-100 hover:text-white hover:bg-orange-500' }} transition-colors duration-200">
                            @include('components.icons.' . $item['icon'], ['class' => 'h-5 w-5 shrink-0'])
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile Sidebar -->
<div class="lg:hidden">
    <!-- Mobile backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-50 bg-gray-900/80" 
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Mobile sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-gradient-to-b from-orange-600 to-orange-700 shadow-xl"
         style="display: none;">
        
        <div class="flex h-16 shrink-0 items-center justify-between px-6">
            <div class="flex items-center gap-x-3">
                <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                    <span class="text-orange-600 font-bold">DC</span>
                </div>
                <span class="text-white font-bold">Driv'n Cook</span>
            </div>
            <button type="button" class="text-orange-200 hover:text-white" @click="sidebarOpen = false">
                <span class="sr-only">Fermer la sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mt-6 px-6">
            <ul role="list" class="space-y-1">
                @foreach($navigationItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium {{ $item['active'] ? 'bg-orange-500 text-white' : 'text-orange-100 hover:text-white hover:bg-orange-500' }} transition-colors duration-200"
                           @click="sidebarOpen = false">
                            @include('components.icons.' . $item['icon'], ['class' => 'h-5 w-5 shrink-0'])
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>
@endif
@endauth
