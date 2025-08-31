@php 
$statusColors = [
    'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'in_maintenance' => 'bg-amber-100 text-amber-800 border-amber-200',
    'maintenance' => 'bg-amber-100 text-amber-800 border-amber-200',
    'retired' => 'bg-red-100 text-red-800 border-red-200',
    'pending' => 'bg-blue-100 text-blue-800 border-blue-200',
    'inactive' => 'bg-gray-100 text-gray-800 border-gray-200'
];
@endphp
@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.nav.trucks'), 'url' => route('bo.trucks.index')],
        ['title' => __('ui.deployment.calendar')]
    ]" />

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.deployment.calendar') }}</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <div id="date-navigation" class="flex items-center bg-white border border-gray-300 rounded-lg shadow-sm">
                    <button id="prev-month" class="p-2 hover:bg-gray-100 rounded-l-lg">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div id="current-month" class="px-3 py-2 font-medium"></div>
                    <button id="next-month" class="p-2 hover:bg-gray-100 rounded-r-lg">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <button id="today-btn" class="px-3 py-2 border border-gray-300 bg-white text-sm font-medium rounded-lg hover:bg-gray-50">
                    {{ __('ui.today') }}
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Calendar view -->
            <div id="calendar" class="grid grid-cols-7 gap-1 overflow-x-auto">
                <!-- Day headers -->
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.mon') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.tue') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.wed') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.thu') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.fri') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.sat') }}</div>
                <div class="text-center font-medium text-gray-500 text-sm p-2">{{ __('ui.days.sun') }}</div>
                
                <!-- Calendar cells will be added dynamically via JavaScript -->
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.deployment.upcoming') }}</h3>
            </div>
        </div>
        
        <div id="upcoming-deployments" class="divide-y divide-gray-200">
            <!-- Loading state -->
            <div class="p-6 text-center" id="loading-deployments">
                <svg class="animate-spin mx-auto h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">{{ __('ui.loading') }}</p>
            </div>
            
            <!-- No deployments message -->
            <div class="hidden p-6 text-center" id="no-deployments">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.deployment.no_deployments') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('ui.deployment.no_upcoming') }}</p>
            </div>
            
            <!-- Deployment template will be cloned by JavaScript -->
            <template id="deployment-template">
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-full bg-gray-100">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-medium text-gray-900 deployment-location"></h4>
                                    <span class="deployment-status inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium rounded-full"></span>
                                </div>
                                <div class="mt-1 flex items-center gap-4">
                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="deployment-truck"></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="deployment-dates"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="deployment-actions">
                            <!-- Actions will be added dynamically based on status -->
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calendar setup
    const calendar = document.getElementById('calendar');
    const currentMonthEl = document.getElementById('current-month');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const todayBtn = document.getElementById('today-btn');
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    
    // Mock deployment data - replace with actual API call
    const deployments = [
        // Sample data for demonstration
        {
            id: '1',
            truck_id: 'TRK-1234',
            truck_code: 'TRK-1234',
            location_text: 'Paris',
            planned_start_at: '2025-08-28T09:00:00',
            planned_end_at: '2025-08-28T18:00:00',
            status: 'planned'
        },
        {
            id: '2',
            truck_id: 'TRK-5678',
            truck_code: 'TRK-5678',
            location_text: 'Lyon',
            planned_start_at: '2025-08-31T10:00:00',
            planned_end_at: '2025-09-01T20:00:00',
            status: 'open'
        }
    ];
    
    function renderCalendar() {
        // Clear previous days
        const dayElements = calendar.querySelectorAll('.calendar-day');
        dayElements.forEach(el => el.remove());
        
        // Set current month/year in header
        const monthNames = [
            "{{ __('ui.months.january') }}",
            "{{ __('ui.months.february') }}",
            "{{ __('ui.months.march') }}",
            "{{ __('ui.months.april') }}",
            "{{ __('ui.months.may') }}",
            "{{ __('ui.months.june') }}",
            "{{ __('ui.months.july') }}",
            "{{ __('ui.months.august') }}",
            "{{ __('ui.months.september') }}",
            "{{ __('ui.months.october') }}",
            "{{ __('ui.months.november') }}",
            "{{ __('ui.months.december') }}"
        ];
        currentMonthEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        // Get first day of month and number of days
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        // Adjust for Monday as first day of week (0 = Monday, 6 = Sunday)
        const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Add empty cells for days before first of month
        for (let i = 0; i < adjustedFirstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day h-24 border border-gray-200 bg-gray-50';
            calendar.appendChild(emptyDay);
        }
        
        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day min-h-24 border border-gray-200 p-2';
            
            // Check if this is today
            const isToday = day === new Date().getDate() && 
                            currentMonth === new Date().getMonth() && 
                            currentYear === new Date().getFullYear();
            
            if (isToday) {
                dayEl.classList.add('bg-orange-50', 'border-orange-200');
            }
            
            // Add day number
            const dayNumber = document.createElement('div');
            dayNumber.className = `text-right text-sm ${isToday ? 'font-bold text-orange-600' : 'text-gray-700'}`;
            dayNumber.textContent = day;
            dayEl.appendChild(dayNumber);
            
            // Add any deployments for this day
            const currentDayDate = new Date(currentYear, currentMonth, day);
            const dayDeployments = deployments.filter(d => {
                const startDate = new Date(d.planned_start_at);
                const endDate = new Date(d.planned_end_at);
                return currentDayDate >= new Date(startDate.setHours(0,0,0,0)) && 
                       currentDayDate <= new Date(endDate.setHours(23,59,59,999));
            });
            
            if (dayDeployments.length > 0) {
                const deploymentList = document.createElement('div');
                deploymentList.className = 'mt-1 space-y-1';
                
                dayDeployments.forEach(deployment => {
                    const statusColors = {
                        'planned': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'open': 'bg-blue-100 text-blue-800 border-blue-200',
                        'closed': 'bg-green-100 text-green-800 border-green-200',
                        'cancelled': 'bg-gray-100 text-gray-800 border-gray-200'
                    };
                    
                    const deploymentEl = document.createElement('div');
                    deploymentEl.className = `px-1.5 py-1 text-xs rounded ${statusColors[deployment.status] || 'bg-gray-100'} truncate`;
                    deploymentEl.textContent = `${deployment.truck_code}: ${deployment.location_text}`;
                    deploymentEl.title = `${deployment.location_text} (${new Date(deployment.planned_start_at).toLocaleTimeString()} - ${new Date(deployment.planned_end_at).toLocaleTimeString()})`;
                    deploymentList.appendChild(deploymentEl);
                });
                
                dayEl.appendChild(deploymentList);
            }
            
            calendar.appendChild(dayEl);
        }
    }
    
    function renderUpcomingDeployments() {
        const upcomingContainer = document.getElementById('upcoming-deployments');
        const loadingEl = document.getElementById('loading-deployments');
        const noDeploymentsEl = document.getElementById('no-deployments');
        const template = document.getElementById('deployment-template');
        
        // Simulate loading
        setTimeout(() => {
            loadingEl.classList.add('hidden');
            
            if (deployments.length === 0) {
                noDeploymentsEl.classList.remove('hidden');
                return;
            }
            
            // Sort deployments by start date
            const sortedDeployments = [...deployments].sort((a, b) => 
                new Date(a.planned_start_at) - new Date(b.planned_start_at)
            );
            
            sortedDeployments.forEach(deployment => {
                const deploymentEl = template.content.cloneNode(true).firstElementChild;
                
                // Set content
                deploymentEl.querySelector('.deployment-location').textContent = deployment.location_text;
                deploymentEl.querySelector('.deployment-truck').textContent = deployment.truck_code;
                
                // Format dates
                const startDate = new Date(deployment.planned_start_at);
                const endDate = new Date(deployment.planned_end_at);
                const formatOptions = { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                deploymentEl.querySelector('.deployment-dates').textContent = 
                    `${startDate.toLocaleDateString('fr-FR', formatOptions)} - ${endDate.toLocaleDateString('fr-FR', formatOptions)}`;
                
                // Set status badge
                const statusEl = deploymentEl.querySelector('.deployment-status');
                const statusColors = {
                    'planned': 'bg-yellow-100 text-yellow-800 border-yellow-200 border',
                    'open': 'bg-blue-100 text-blue-800 border-blue-200 border',
                    'closed': 'bg-green-100 text-green-800 border-green-200 border',
                    'cancelled': 'bg-gray-100 text-gray-800 border-gray-200 border'
                };
                statusEl.className = `deployment-status inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium rounded-full ${statusColors[deployment.status] || 'bg-gray-100'}`;
                
                // Add status dot
                const statusDot = document.createElement('span');
                statusDot.className = `w-1.5 h-1.5 rounded-full ${
                    deployment.status === 'planned' ? 'bg-yellow-500' :
                    deployment.status === 'open' ? 'bg-blue-500' :
                    deployment.status === 'closed' ? 'bg-green-500' :
                    'bg-gray-500'
                }`;
                statusEl.prepend(statusDot);
                
                // Add status text
                const statusText = document.createTextNode(
                    deployment.status === 'planned' ? "{{ __('ui.deployment.status.planned') }}" :
                    deployment.status === 'open' ? "{{ __('ui.deployment.status.open') }}" :
                    deployment.status === 'closed' ? "{{ __('ui.deployment.status.closed') }}" :
                    "{{ __('ui.deployment.status.cancelled') }}"
                );
                statusEl.appendChild(statusText);
                
                // Add actions based on status
                const actionsEl = deploymentEl.querySelector('.deployment-actions');
                
                if (deployment.status === 'planned') {
                    const openBtn = document.createElement('button');
                    openBtn.className = 'text-sm text-blue-600 hover:text-blue-800 font-medium';
                    openBtn.textContent = "{{ __('ui.actions.open') }}";
                    actionsEl.appendChild(openBtn);
                } else if (deployment.status === 'open') {
                    const closeBtn = document.createElement('button');
                    closeBtn.className = 'text-sm text-green-600 hover:text-green-800 font-medium';
                    closeBtn.textContent = "{{ __('ui.actions.close') }}";
                    actionsEl.appendChild(closeBtn);
                }
                
                upcomingContainer.appendChild(deploymentEl);
            });
        }, 1000);
    }
    
    // Initialize calendar and upcoming deployments
    renderCalendar();
    renderUpcomingDeployments();
    
    // Event listeners for navigation
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });
    
    todayBtn.addEventListener('click', () => {
        const today = new Date();
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        renderCalendar();
    });
});
</script>
@endsection
