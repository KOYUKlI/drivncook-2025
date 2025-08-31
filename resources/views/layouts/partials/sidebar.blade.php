{{-- Desktop Sidebar --}}
<nav class="flex flex-col h-full py-3">
    <div class="flex-1 space-y-0.5 px-2">
        @role('admin|warehouse|fleet|tech')
            {{-- Back Office Navigation --}}
            <div class="space-y-0.5">
                <x-nav.link :href="route('bo.dashboard')" :active="request()->routeIs('bo.dashboard')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v6m4-6v6m4-6v6" />
                    </svg>
                    <span class="text-sm">{{ __('ui.dashboard') }}</span>
                </x-nav.link>

                @role('admin')
                    <x-nav.link :href="route('bo.franchisees.index')" :active="request()->routeIs('bo.franchisees.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <span class="text-sm">{{ __('ui.franchisees') }}</span>
                    </x-nav.link>
                    
                    <x-nav.link :href="route('bo.applications.index')" :active="request()->routeIs('bo.applications.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm">{{ __('ui.applications') }}</span>
                    </x-nav.link>
                @endrole

                @role('admin|fleet')
                    <x-nav.link :href="route('bo.trucks.index')" :active="request()->routeIs('bo.trucks.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm">{{ __('ui.trucks') }}</span>
                    </x-nav.link>
                @endrole

                @role('admin|warehouse')
                    <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm">{{ __('ui.bo.warehouses.title') }}</span>
                    </x-nav.link>
                    
                    <x-nav.link :href="route('bo.stock-items.index')" :active="request()->routeIs('bo.stock-items.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-sm">{{ __('ui.bo.stock_items.title') }}</span>
                    </x-nav.link>

                    <x-nav.link :href="route('bo.warehouses.inventory')" :active="request()->routeIs('bo.warehouses.inventory*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="text-sm">{{ __('ui.inventory.title') }}</span>
                    </x-nav.link>

                    <x-nav.link :href="route('bo.stock-movements.create')" :active="request()->routeIs('bo.stock-movements.*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span class="text-sm">{{ __('ui.inventory.create_movement') }}</span>
                    </x-nav.link>

                    <!-- Séparateur visuel -->
                    <div class="border-t border-gray-200 my-1"></div>

                    <x-nav.link :href="route('bo.purchase-orders.index')" :active="request()->routeIs('bo.purchase-orders.index')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-sm">{{ __('ui.bo.purchase_orders.title') }}</span>
                    </x-nav.link>
                    
                    <x-nav.link :href="route('bo.purchase-orders.create')" :active="request()->routeIs('bo.purchase-orders.create')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-sm">{{ __('ui.bo.purchase_orders.create') }}</span>
                    </x-nav.link>
                    
                    <x-nav.link :href="route('bo.purchase-orders.compliance-report')" :active="request()->routeIs('bo.purchase-orders.compliance-report')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-sm">{{ __('ui.bo.purchase_orders.compliance_report.title') }}</span>
                    </x-nav.link>
                    
                    <x-nav.link :href="route('bo.reports.monthly')" :active="request()->routeIs('bo.reports.monthly*')" class="group transition-all duration-150 flex items-center py-2">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        <span class="text-sm">{{ __('ui.monthly_sales_reports') }}</span>
                    </x-nav.link>
                @endrole
            </div>
        @endrole

        @role('franchisee')
            {{-- Front Office Navigation --}}
            <div class="space-y-0.5">
                <x-nav.link :href="route('fo.dashboard')" :active="request()->routeIs('fo.dashboard')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v6m4-6v6m4-6v6" />
                    </svg>
                    <span class="text-sm">{{ __('ui.dashboard') }}</span>
                </x-nav.link>
                
                <x-nav.link :href="route('fo.sales.index')" :active="request()->routeIs('fo.sales.index')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-sm">{{ __('ui.my_sales') }}</span>
                </x-nav.link>
                
                <x-nav.link :href="route('fo.sales.create')" :active="request()->routeIs('fo.sales.create')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="text-sm">{{ __('ui.new_sale') }}</span>
                </x-nav.link>
                
                <x-nav.link :href="route('fo.reports.index')" :active="request()->routeIs('fo.reports.*')" class="group transition-all duration-150 flex items-center py-2">
                    <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <span class="text-sm">{{ __('ui.fo.reports.title') }}</span>
                </x-nav.link>
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
            <nav class="mt-2 px-3 space-y-1" aria-label="{{ __('ui.side_navigation') }}">
                @role('admin|warehouse|fleet|tech')
                    <x-nav.link :href="route('bo.dashboard')" :active="request()->routeIs('bo.dashboard')" mobile>
                        {{ __('ui.dashboard') }}
                    </x-nav.link>

                    @role('admin')
                        <x-nav.link :href="route('bo.franchisees.index')" :active="request()->routeIs('bo.franchisees.*')" mobile>
                            {{ __('ui.franchisees') }}
                        </x-nav.link>
                        <x-nav.link :href="route('bo.applications.index')" :active="request()->routeIs('bo.applications.*')" mobile>
                            {{ __('ui.applications') }}
                        </x-nav.link>
                    @endrole

                    @role('admin|fleet')
                        <x-nav.link :href="route('bo.trucks.index')" :active="request()->routeIs('bo.trucks.*')" mobile>
                            {{ __('ui.trucks') }}
                        </x-nav.link>
                    @endrole

                    @role('admin|warehouse')
                        <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.*')" mobile>
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
                        <x-nav.link :href="route('bo.purchase-orders.index')" :active="request()->routeIs('bo.purchase-orders.*')" mobile>
                            {{ __('ui.bo.purchase_orders.title') }}
                        </x-nav.link>
                        <x-nav.link :href="route('bo.purchase-orders.create')" :active="request()->routeIs('bo.purchase-orders.create')" mobile>
                            {{ __('ui.bo.purchase_orders.create') }}
                        </x-nav.link>
                        <x-nav.link :href="route('bo.purchase-orders.compliance-report')" :active="request()->routeIs('bo.purchase-orders.compliance-report')" mobile>
                            {{ __('ui.bo.purchase_orders.compliance_report.title') }}
                        </x-nav.link>
                        <x-nav.link :href="route('bo.reports.monthly')" :active="request()->routeIs('bo.reports.monthly*')" mobile>
                            {{ __('ui.monthly_sales_reports') }}
                        </x-nav.link>
                    @endrole
                @endrole

                @role('franchisee')
                    <x-nav.link :href="route('fo.dashboard')" :active="request()->routeIs('fo.dashboard')" mobile>
                        {{ __('ui.dashboard') }}
                    </x-nav.link>
                    <x-nav.link :href="route('fo.sales.index')" :active="request()->routeIs('fo.sales.*')" mobile>
                        {{ __('ui.my_sales') }}
                    </x-nav.link>
                    <x-nav.link :href="route('fo.sales.create')" :active="request()->routeIs('fo.sales.create')" mobile>
                        {{ __('ui.new_sale') }}
                    </x-nav.link>
                    <x-nav.link :href="route('fo.reports.index')" :active="request()->routeIs('fo.reports.*')" mobile>
                        {{ __('ui.fo.reports.title') }}
                    </x-nav.link>
                @endrole
            </nav>
        </div>
    </div>
</div>
