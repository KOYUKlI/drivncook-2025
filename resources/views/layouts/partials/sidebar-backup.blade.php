@php
    $currentRoute = request()->route()->getName();
    $user = Auth::user();
    $userRoles = $user->getRoleNames();
    
    // Utiliser les données injectées par le provider, ou valeurs par défaut
    $context = $sidebarData ?? [
        'applications_count' => 0,
        'franchisees_count' => 0,
        'trucks_count' => 0,
    ];
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:bg-white lg:border-r lg:border-gray-200 lg:pt-16 lg:z-30">
    <div class="flex flex-col flex-grow overflow-y-auto">
        <!-- User Info Section -->
        <div class="flex items-center flex-shrink-0 px-4 py-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                <span class="text-white font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $userRoles->first() }}</p>
                @if($user->hasRole('franchisee') && $user->franchisee)
                    <p class="text-xs text-indigo-600 truncate">{{ $user->franchisee->territory_name ?? __('ui.labels.territory') }}</p>
                @endif
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-4 space-y-1">
            @role('admin')
                <!-- Admin Navigation -->
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <x-nav.link 
                        href="{{ route('bo.dashboard') }}" 
                        :active="$currentRoute === 'bo.dashboard'"
                        icon="chart-bar"
                    >
                        {{ __('ui.nav.dashboard') }}
                    </x-nav.link>

                    <!-- Applications Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.franchise_management') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.applications.index') }}" 
                            :active="str_contains($currentRoute, 'applications')"
                            icon="document-text"
                            :badge="$context['applications_count'] > 0 ? $context['applications_count'] : null"
                        >
                            {{ __('ui.nav.applications') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.franchisees.index') }}" 
                            :active="str_contains($currentRoute, 'franchisees')"
                            icon="users"
                        >
                            {{ __('ui.nav.franchisees') }}
                        </x-nav.link>
                    </div>

                    <!-- Fleet Management Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.fleet_management') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.trucks.index') }}" 
                            :active="str_contains($currentRoute, 'trucks')"
                            icon="truck"
                        >
                            {{ __('ui.nav.trucks') }}
                        </x-nav.link>
                    </div>

                    <!-- Inventory Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.inventory') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.warehouses.index') }}" 
                            :active="str_contains($currentRoute, 'warehouses')"
                            icon="building-storefront"
                        >
                            {{ __('ui.nav.warehouses') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.stock-items.index') }}" 
                            :active="str_contains($currentRoute, 'stock-items')"
                            icon="cube"
                        >
                            {{ __('ui.nav.stock_items') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.purchase-orders.index') }}" 
                            :active="str_contains($currentRoute, 'purchase-orders')"
                            icon="shopping-bag"
                        >
                            {{ __('ui.nav.purchase_orders') }}
                        </x-nav.link>
                    </div>

                    <!-- Reports Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.labels.reports') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.reports.monthly') }}" 
                            :active="$currentRoute === 'bo.reports.monthly'"
                            icon="chart-pie"
                        >
                            {{ __('ui.nav.reports_monthly') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.purchase-orders.compliance-report') }}" 
                            :active="$currentRoute === 'bo.purchase-orders.compliance-report'"
                            icon="shield-check"
                        >
                            {{ __('ui.nav.reports_compliance') }}
                        </x-nav.link>
                    </div>
                </div>
            @endrole

            @role('warehouse')
                <!-- Warehouse Navigation -->
                <div class="space-y-1">
                    <x-nav.link 
                        href="{{ route('bo.dashboard') }}" 
                        :active="$currentRoute === 'bo.dashboard'"
                        icon="chart-bar"
                    >
                        {{ __('ui.nav.dashboard') }}
                    </x-nav.link>

                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.inventory') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.warehouses.index') }}" 
                            :active="str_contains($currentRoute, 'warehouses')"
                            icon="building-storefront"
                        >
                            {{ __('ui.nav.warehouses') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.stock-items.index') }}" 
                            :active="str_contains($currentRoute, 'stock-items')"
                            icon="cube"
                        >
                            {{ __('ui.nav.stock_items') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('bo.purchase-orders.index') }}" 
                            :active="str_contains($currentRoute, 'purchase-orders')"
                            icon="shopping-bag"
                        >
                            {{ __('ui.nav.purchase_orders') }}
                        </x-nav.link>
                    </div>
                </div>
            @endrole

            @role('fleet')
                <!-- Fleet Navigation -->
                <div class="space-y-1">
                    <x-nav.link 
                        href="{{ route('bo.dashboard') }}" 
                        :active="$currentRoute === 'bo.dashboard'"
                        icon="chart-bar"
                    >
                        {{ __('ui.nav.dashboard') }}
                    </x-nav.link>

                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.fleet_management') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.trucks.index') }}" 
                            :active="str_contains($currentRoute, 'trucks')"
                            icon="truck"
                        >
                            {{ __('ui.nav.trucks') }}
                        </x-nav.link>
                    </div>
                </div>
            @endrole

            @role('tech')
                <!-- Tech Navigation -->
                <div class="space-y-1">
                    <x-nav.link 
                        href="{{ route('bo.dashboard') }}" 
                        :active="$currentRoute === 'bo.dashboard'"
                        icon="chart-bar"
                    >
                        {{ __('ui.nav.dashboard') }}
                    </x-nav.link>

                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.maintenance') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('bo.trucks.index') }}" 
                            :active="str_contains($currentRoute, 'trucks')"
                            icon="truck"
                        >
                            {{ __('ui.nav.trucks') }}
                        </x-nav.link>
                    </div>
                </div>
            @endrole

            @role('franchisee')
                <!-- Franchisee Navigation -->
                <div class="space-y-1">
                    <x-nav.link 
                        href="{{ route('fo.dashboard') }}" 
                        :active="$currentRoute === 'fo.dashboard'"
                        icon="chart-bar"
                    >
                        {{ __('ui.nav.dashboard') }}
                    </x-nav.link>

                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ __('ui.sections.sales') }}
                        </h3>
                        
                        <x-nav.link 
                            href="{{ route('fo.sales.index') }}" 
                            :active="str_contains($currentRoute, 'fo.sales')"
                            icon="currency-dollar"
                        >
                            {{ __('ui.nav.fo_sales') }}
                        </x-nav.link>

                        <x-nav.link 
                            href="{{ route('fo.reports.index') }}" 
                            :active="str_contains($currentRoute, 'fo.reports')"
                            icon="chart-pie"
                        >
                            {{ __('ui.nav.fo_reports') }}
                        </x-nav.link>
                    </div>

                    @if($user->franchisee && $user->franchisee->truck)
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                {{ __('ui.sections.my_truck') }}
                            </h3>
                            
                            <x-nav.link 
                                href="#" 
                                :active="false"
                                icon="truck"
                            >
                                {{ $user->franchisee->truck->registration ?? __('ui.nav.my_truck') }}
                            </x-nav.link>
                        </div>
                    @endif
                </div>
            @endrole
        </nav>

        <!-- Quick Stats for Admin -->
        @if($user->hasRole('admin'))
            <div class="px-4 py-4 border-t border-gray-100">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4">
                    <h4 class="text-xs font-medium text-gray-900 mb-3">{{ __('ui.quick_stats') }}</h4>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="flex justify-between items-center">
                            <span>{{ __('ui.labels.applications') }}</span>
                            <span class="font-medium text-indigo-600">{{ $context['applications_count'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>{{ __('ui.labels.franchisees') }}</span>
                            <span class="font-medium text-indigo-600">{{ $context['franchisees_count'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>{{ __('ui.labels.trucks') }}</span>
                            <span class="font-medium text-indigo-600">{{ $context['trucks_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</aside>

<!-- Mobile Sidebar -->
<div class="lg:hidden">
    <!-- Mobile sidebar overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75"
        @click="sidebarOpen = false"
        style="display: none;">
    </div>

    <!-- Mobile sidebar -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg"
        style="display: none;">
        
        <!-- Mobile sidebar content -->
        <div class="flex flex-col h-full">
            <!-- Mobile header -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-2">
                        <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <span class="text-lg font-semibold">Menu</span>
                </div>
                <button @click="sidebarOpen = false" class="p-2 rounded-md text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile navigation (mirror of desktop) -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-4 space-y-1">
                    <!-- Same navigation structure as desktop but with mobile="true" attribute -->
                    @role('admin')
                        <x-nav.link 
                            href="{{ route('bo.dashboard') }}" 
                            :active="$currentRoute === 'bo.dashboard'"
                            icon="chart-bar"
                            mobile="true"
                        >
                            {{ __('ui.nav.dashboard') }}
                        </x-nav.link>
                        
                        <div class="mt-4">
                            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                {{ __('ui.sections.franchise_management') }}
                            </h3>
                            
                            <x-nav.link 
                                href="{{ route('bo.applications.index') }}" 
                                :active="str_contains($currentRoute, 'applications')"
                                icon="document-text"
                                mobile="true"
                            >
                                {{ __('ui.nav.applications') }}
                            </x-nav.link>

                            <x-nav.link 
                                href="{{ route('bo.franchisees.index') }}" 
                                :active="str_contains($currentRoute, 'franchisees')"
                                icon="users"
                                mobile="true"
                            >
                                {{ __('ui.nav.franchisees') }}
                            </x-nav.link>
                        </div>

                        <!-- Additional mobile sections... -->
                    @endrole

                    <!-- Other roles mobile navigation... -->
                </nav>
            </div>
        </div>
    </div>
</div>
