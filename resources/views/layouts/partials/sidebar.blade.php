{{-- Desktop Sidebar --}}
<nav class="flex flex-col h-full py-3">
    <div class="flex-1 space-y-1 px-2" x-data="{
        openAdmin: {{ request()->routeIs('bo.franchisees.*') || request()->routeIs('bo.applications.*') ? 'true' : 'false' }},
        openFleet: {{ request()->routeIs('bo.trucks.*') ? 'true' : 'false' }},
        openWarehouse: {{ request()->routeIs('bo.warehouses.*') || request()->routeIs('bo.stock-items.*') || request()->routeIs('bo.stock-movements.*') || request()->routeIs('bo.warehouses.inventory*') ? 'true' : 'false' }},
    openPurchasing: {{ (request()->routeIs('bo.replenishments.*')) ? 'true' : 'false' }},
        openReports: {{ request()->routeIs('bo.reports.*') ? 'true' : 'false' }},
    }">
        @role('admin|warehouse|fleet|tech')
            {{-- Back Office --}}
            <div class="space-y-1">
                <x-nav.link :href="route('bo.dashboard')" :active="request()->routeIs('bo.dashboard')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v6m4-6v6m4-6v6" />
                    </svg>
                    <span class="text-sm">{{ __('ui.dashboard') }}</span>
                </x-nav.link>

                @role('admin')
                    <div>
                        <button type="button" @click="openAdmin = !openAdmin" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-2 py-2 hover:text-gray-700">
                            <span>Administration</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openAdmin }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openAdmin" x-collapse>
                            <div class="pl-2 space-y-0.5">
                                <x-nav.link :href="route('bo.franchisees.index')" :active="request()->routeIs('bo.franchisees.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                    <span class="text-sm">{{ __('ui.franchisees') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.applications.index')" :active="request()->routeIs('bo.applications.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-sm">{{ __('ui.applications') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.audit.index')" :active="request()->routeIs('bo.audit.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    <span class="text-sm">{{ __('audit.title') }}</span>
                                </x-nav.link>
                            </div>
                        </div>
                    </div>
                @endrole

                @role('admin|fleet')
                    <div>
                        <button type="button" @click="openFleet = !openFleet" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-2 py-2 hover:text-gray-700">
                            <span>Flotte</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openFleet }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openFleet" x-collapse>
                            <div class="pl-2 space-y-0.5">
                                <x-nav.link :href="route('bo.trucks.index')" :active="request()->routeIs('bo.trucks.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">{{ __('ui.trucks') }}</span>
                                </x-nav.link>
                            </div>
                        </div>
                    </div>
                @endrole

                @role('admin|warehouse')
                    <div>
                        <button type="button" @click="openWarehouse = !openWarehouse" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-2 py-2 hover:text-gray-700">
                            <span>Entrepôts & Stock</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openWarehouse }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openWarehouse" x-collapse>
                            <div class="pl-2 space-y-0.5">
                                <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.index')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="text-sm">{{ __('ui.bo.warehouses.title') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.stock-items.index')" :active="request()->routeIs('bo.stock-items.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <span class="text-sm">{{ __('ui.bo.stock_items.title') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.warehouses.inventory')" :active="request()->routeIs('bo.warehouses.inventory*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    <span class="text-sm">{{ __('ui.inventory.title') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.stock-movements.create')" :active="request()->routeIs('bo.stock-movements.*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    <span class="text-sm">{{ __('ui.inventory.create_movement') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.warehouses.inventory')" :active="request()->routeIs('bo.warehouses.inventory*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    <span class="text-sm">{{ __('warehouse_dashboard.inventory.dashboard.menu_title') }}</span>
                                </x-nav.link>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="button" @click="openPurchasing = !openPurchasing" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-2 py-2 hover:text-gray-700">
                            <span>Achats</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openPurchasing }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openPurchasing" x-collapse>
                            <div class="pl-2 space-y-0.5">
                                
                                    <x-nav.link :href="route('bo.replenishments.index')" :active="request()->routeIs('bo.replenishments.*')" class="group flex items-center py-2">
                                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                                        <span class="text-sm">{{ __('ui.replenishments.title') }}</span>
                                    </x-nav.link>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="button" @click="openReports = !openReports" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-2 py-2 hover:text-gray-700">
                            <span>Rapports</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openReports }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openReports" x-collapse>
                            <div class="pl-2 space-y-0.5">
                                <x-nav.link :href="route('bo.reports.monthly')" :active="request()->routeIs('bo.reports.monthly*')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    <span class="text-sm">{{ __('ui.monthly_sales_reports') }}</span>
                                </x-nav.link>
                                <x-nav.link :href="route('bo.reports.compliance')" :active="request()->routeIs('bo.reports.compliance')" class="group flex items-center py-2">
                                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                    <span class="text-sm">{{ __('ui.nav.reports_compliance') }}</span>
                                </x-nav.link>
                            </div>
                        </div>
                    </div>
                @endrole
            </div>
        @endrole

        @role('franchisee')
            {{-- Front Office (TEMPORARILY REMOVED FOR REBUILD) --}}
            <div class="space-y-1">
                <p class="text-sm text-gray-500 px-2 py-2">
                    {{ __('L\'interface franchisé est en cours de reconstruction.') }}
                </p>
            </div>
        @endrole
    </div>
</nav>

{{-- Mobile Sidebar --}}
<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 z-40 lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
    
    {{-- Sidebar content --}}
    <div 
        class="relative flex-1 flex flex-col max-w-xs w-full bg-white"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
    >
        {{-- Close button --}}
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button 
                @click="sidebarOpen = false"
                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            >
                <span class="sr-only">{{ __('ui.close_sidebar') }}</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile navigation --}}
        <div class="flex-1 h-0 pt-4 pb-4 overflow-y-auto">
            <nav class="mt-2 px-3 space-y-1" aria-label="{{ __('ui.side_navigation') }}" x-data="{
                openAdminM: {{ request()->routeIs('bo.franchisees.*') || request()->routeIs('bo.applications.*') ? 'true' : 'false' }},
                openFleetM: {{ request()->routeIs('bo.trucks.*') ? 'true' : 'false' }},
                openWarehouseM: {{ request()->routeIs('bo.warehouses.*') || request()->routeIs('bo.stock-items.*') || request()->routeIs('bo.stock-movements.*') || request()->routeIs('bo.warehouses.inventory*') ? 'true' : 'false' }},
                openPurchasingM: {{ (request()->routeIs('bo.replenishments.*')) ? 'true' : 'false' }},
                openReportsM: {{ request()->routeIs('bo.reports.*') ? 'true' : 'false' }},
                openFoSalesM: {{ request()->routeIs('fo.sales.*') ? 'true' : 'false' }},
                openFoReportsM: {{ request()->routeIs('fo.reports.*') ? 'true' : 'false' }},
            }">
                @role('admin|warehouse|fleet|tech')
                    <x-nav.link :href="route('bo.dashboard')" :active="request()->routeIs('bo.dashboard')" mobile>
                        {{ __('ui.dashboard') }}
                    </x-nav.link>

                    @role('admin')
                        <button type="button" @click="openAdminM = !openAdminM" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-1 py-2">
                            <span>Administration</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openAdminM }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openAdminM" x-collapse class="pl-2">
                            <x-nav.link :href="route('bo.franchisees.index')" :active="request()->routeIs('bo.franchisees.*')" mobile>
                                {{ __('ui.franchisees') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.applications.index')" :active="request()->routeIs('bo.applications.*')" mobile>
                                {{ __('ui.applications') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.audit.index')" :active="request()->routeIs('bo.audit.*')" mobile>
                                {{ __('audit.title') }}
                            </x-nav.link>
                        </div>
                    @endrole

                    @role('admin|fleet')
                        <button type="button" @click="openFleetM = !openFleetM" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-1 py-2">
                            <span>Flotte</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openFleetM }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openFleetM" x-collapse class="pl-2">
                            <x-nav.link :href="route('bo.trucks.index')" :active="request()->routeIs('bo.trucks.*')" mobile>
                                {{ __('ui.trucks') }}
                            </x-nav.link>
                        </div>
                    @endrole

                    @role('admin|warehouse')
                        <button type="button" @click="openWarehouseM = !openWarehouseM" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-1 py-2">
                            <span>Entrepôts & Stock</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openWarehouseM }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openWarehouseM" x-collapse class="pl-2">
                            <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.index')" mobile>
                                {{ __('ui.bo.warehouses.title') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.stock-items.index')" :active="request()->routeIs('bo.stock-items.*')" mobile>
                                {{ __('ui.bo.stock_items.title') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.warehouses.inventory')" :active="request()->routeIs('bo.warehouses.inventory*')" mobile>
                                {{ __('ui.inventory.title') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.stock-movements.create')" :active="request()->routeIs('bo.stock-movements.*')" mobile>
                                {{ __('ui.inventory.create_movement') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.warehouses.inventory')" :active="request()->routeIs('bo.warehouses.inventory*')" mobile>
                                {{ __('warehouse_dashboard.inventory.dashboard.menu_title') }}
                            </x-nav.link>
                        </div>

                        <button type="button" @click="openPurchasingM = !openPurchasingM" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-1 py-2">
                            <span>Achats</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openPurchasingM }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openPurchasingM" x-collapse class="pl-2">
                            
                                <x-nav.link :href="route('bo.replenishments.index')" :active="request()->routeIs('bo.replenishments.*')" mobile>
                                    {{ __('ui.replenishments.title') }}
                                </x-nav.link>
                        </div>

                        <button type="button" @click="openReportsM = !openReportsM" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-gray-500 px-1 py-2">
                            <span>Rapports</span>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-90': openReportsM }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openReportsM" x-collapse class="pl-2">
                            <x-nav.link :href="route('bo.reports.monthly')" :active="request()->routeIs('bo.reports.monthly*')" mobile>
                                {{ __('ui.monthly_sales_reports') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.reports.compliance')" :active="request()->routeIs('bo.reports.compliance')" mobile>
                                {{ __('ui.nav.reports_compliance') }}
                            </x-nav.link>
                        </div>
                    @endrole
                @endrole

                @role('franchisee')
                    <p class="text-sm text-gray-500 px-1 py-2">
                        {{ __('L\'interface franchisé est en cours de reconstruction.') }}
                    </p>
                @endrole
            </nav>
        </div>
    </div>
</div>
