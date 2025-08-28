{{-- Desktop Sidebar --}}
<aside class="mb-6 lg:mb-0 hidden lg:block">
    <nav aria-label="{{ __('ui.side_navigation') }}" class="space-y-1">
        @role('admin|warehouse|fleet|tech')
            {{-- Back Office Navigation --}}
            <div class="mb-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('ui.back_office') }}
                </h3>
                <x-nav.link :href="route('bo.dashboard')" :active="request()->routeIs('bo.dashboard')">
                    {{ __('ui.dashboard') }}
                </x-nav.link>

                @role('admin')
                    <x-nav.link :href="route('bo.franchisees.index')" :active="request()->routeIs('bo.franchisees.*')">
                        {{ __('ui.franchisees') }}
                    </x-nav.link>
                    <x-nav.link :href="route('bo.applications.index')" :active="request()->routeIs('bo.applications.*')">
                        {{ __('ui.applications') }}
                    </x-nav.link>
                @endrole

                @role('admin|fleet')
                    <x-nav.link :href="route('bo.trucks.index')" :active="request()->routeIs('bo.trucks.*')">
                        {{ __('ui.trucks') }}
                    </x-nav.link>
                @endrole

                @role('admin|warehouse')
                    <x-nav.link :href="route('bo.purchase-orders.index')" :active="request()->routeIs('bo.purchase-orders.*')">
                        {{ __('ui.purchase_orders') }}
                    </x-nav.link>
                    <x-nav.link :href="route('bo.purchase-orders.create')" :active="request()->routeIs('bo.purchase-orders.create')">
                        {{ __('ui.create_purchase_order') }}
                    </x-nav.link>
                    <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.*')">
                        {{ __('ui.warehouses') }}
                    </x-nav.link>
                    <x-nav.link :href="route('bo.stock-items.index')" :active="request()->routeIs('bo.stock-items.*')">
                        {{ __('ui.stock_items') }}
                    </x-nav.link>
                    <x-nav.link :href="route('bo.reports.monthly')" :active="request()->routeIs('bo.reports.monthly*')">
                        {{ __('ui.monthly_sales_reports') }}
                    </x-nav.link>
                @endrole
            </div>
        @endrole

        @role('franchisee')
            {{-- Front Office Navigation --}}
            <div class="mb-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('ui.my_franchise') }}
                </h3>
                <x-nav.link :href="route('fo.dashboard')" :active="request()->routeIs('fo.dashboard')">
                    {{ __('ui.dashboard') }}
                </x-nav.link>
                <x-nav.link :href="route('fo.sales.index')" :active="request()->routeIs('fo.sales.index')">
                    {{ __('ui.my_sales') }}
                </x-nav.link>
                <x-nav.link :href="route('fo.sales.create')" :active="request()->routeIs('fo.sales.create')">
                    {{ __('ui.new_sale') }}
                </x-nav.link>
                <x-nav.link :href="route('fo.reports.index')" :active="request()->routeIs('fo.reports.*')">
                    {{ __('ui.my_reports') }}
                </x-nav.link>
            </div>
        @endrole
    </nav>
</aside>

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
        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <nav class="mt-5 px-2 space-y-1" aria-label="{{ __('ui.side_navigation') }}">
                @role('admin|warehouse|fleet|tech')
                    {{-- Back Office Mobile Navigation --}}
                    <div class="mb-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-3">
                            {{ __('ui.back_office') }}
                        </h3>
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
                            <x-nav.link :href="route('bo.purchase-orders.index')" :active="request()->routeIs('bo.purchase-orders.*')" mobile>
                                {{ __('ui.purchase_orders') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.purchase-orders.create')" :active="request()->routeIs('bo.purchase-orders.create')" mobile>
                                {{ __('ui.create_purchase_order') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.warehouses.index')" :active="request()->routeIs('bo.warehouses.*')" mobile>
                                {{ __('ui.warehouses') }}
                            </x-nav.link>
                            <x-nav.link :href="route('bo.stock-items.index')" :active="request()->routeIs('bo.stock-items.*')" mobile>
                                {{ __('ui.stock_items') }}
                            </x-nav.link>
                        @endrole
                    </div>
                @endrole

                @role('franchisee')
                    {{-- Front Office Mobile Navigation --}}
                    <div class="mb-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-3">
                            {{ __('ui.my_franchise') }}
                        </h3>
                        <x-nav.link :href="route('fo.dashboard')" :active="request()->routeIs('fo.dashboard')" mobile>
                            {{ __('ui.dashboard') }}
                        </x-nav.link>
                        <x-nav.link :href="route('fo.sales.index')" :active="request()->routeIs('fo.sales.*')" mobile>
                            {{ __('ui.sales') }}
                        </x-nav.link>
                        <x-nav.link :href="route('fo.reports.index')" :active="request()->routeIs('fo.reports.*')" mobile>
                            {{ __('ui.reports') }}
                        </x-nav.link>
                    </div>
                @endrole
            </nav>
        </div>
    </div>
</div>
