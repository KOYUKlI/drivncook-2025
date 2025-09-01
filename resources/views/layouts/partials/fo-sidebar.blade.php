@role('franchisee')
<nav class="flex flex-col h-full py-3">
    <div class="flex-1 space-y-1 px-2" x-data="{
        openSales: {{ request()->routeIs('fo.sales.*') ? 'true' : 'false' }},
        openReports: {{ request()->routeIs('fo.reports.*') ? 'true' : 'false' }}
    }">
        {{-- Franchisee Office --}}
        <div class="space-y-1">
            <x-nav.link :href="route('fo.dashboard')" :active="request()->routeIs('fo.dashboard')" class="group transition-all duration-150 flex items-center py-2">
                <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-sm">{{ __('ui.fo.nav.dashboard') }}</span>
            </x-nav.link>

            {{-- Truck Section --}}
            <x-nav.link :href="route('fo.truck.show')" :active="request()->routeIs('fo.truck.*')" class="group transition-all duration-150 flex items-center py-2">
                <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                <span class="text-sm">{{ __('ui.nav.fo_truck') }}</span>
            </x-nav.link>

            {{-- Sales Section --}}
            <div>
                <button type="button" 
                        @click="openSales = !openSales"
                        class="w-full flex items-center justify-between text-left text-sm text-gray-600 hover:text-gray-900 px-2 py-2 group hover:bg-gray-50 rounded-md transition-colors duration-150"
                        aria-expanded="openSales"
                        aria-controls="sales-menu">
                    <div class="flex items-center">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('ui.fo.nav.sales') }}</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform duration-200" 
                         :class="{'rotate-90': openSales, 'rotate-0': !openSales}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openSales" 
                    x-collapse
                    class="mt-1 pl-7 space-y-1"
                    id="sales-menu">
                    <x-nav.link :href="route('fo.sales.index')" :active="request()->routeIs('fo.sales.index')" class="block py-2 text-sm">
                        {{ __('ui.fo.nav.my_sales') }}
                    </x-nav.link>
                    <x-nav.link :href="route('fo.sales.create')" :active="request()->routeIs('fo.sales.create')" class="block py-2 text-sm">
                        {{ __('ui.fo.nav.new_sale') }}
                    </x-nav.link>
                </div>
            </div>

            {{-- Reports Section --}}
            <div>
                <button type="button" 
                        @click="openReports = !openReports"
                        class="w-full flex items-center justify-between text-left text-sm text-gray-600 hover:text-gray-900 px-2 py-2 group hover:bg-gray-50 rounded-md transition-colors duration-150"
                        aria-expanded="openReports"
                        aria-controls="reports-menu">
                    <div class="flex items-center">
                        <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>{{ __('ui.fo.nav.reports') }}</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform duration-200" 
                         :class="{'rotate-90': openReports, 'rotate-0': !openReports}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openReports" 
                    x-collapse
                    class="mt-1 pl-7 space-y-1"
                    id="reports-menu">
                    <x-nav.link :href="route('fo.reports.index')" :active="request()->routeIs('fo.reports.index')" class="block py-2 text-sm">
                        {{ __('ui.fo.nav.monthly_reports') }}
                    </x-nav.link>
                </div>
            </div>
            
            {{-- Account --}}
            <x-nav.link :href="route('fo.account.edit')" :active="request()->routeIs('fo.account.*')" class="group transition-all duration-150 flex items-center py-2">
                <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm">{{ __('ui.fo.nav.account') }}</span>
            </x-nav.link>
        </div>
    </div>
</nav>
@endrole
