<!-- Deployment Filters Component -->
<div class="bg-white p-4 rounded-lg shadow mb-4">
    <form action="{{ request()->url() }}" method="GET">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">{{ __('deployment.filters.title') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Date Range Filter -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.filters.date_range') }}</label>
                <div class="flex space-x-2">
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.filters.status') }}</label>
                <select id="status" name="status[]" multiple 
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="planned" {{ in_array('planned', (array)request('status')) ? 'selected' : '' }}>
                        {{ __('deployment.status.planned') }}
                    </option>
                    <option value="open" {{ in_array('open', (array)request('status')) ? 'selected' : '' }}>
                        {{ __('deployment.status.open') }}
                    </option>
                    <option value="closed" {{ in_array('closed', (array)request('status')) ? 'selected' : '' }}>
                        {{ __('deployment.status.closed') }}
                    </option>
                    <option value="cancelled" {{ in_array('cancelled', (array)request('status')) ? 'selected' : '' }}>
                        {{ __('deployment.status.cancelled') }}
                    </option>
                </select>
            </div>
            
            <!-- Location Filter -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.filters.location') }}</label>
                <input type="text" id="location" name="location" value="{{ request('location') }}" 
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            
            <!-- Franchisee Filter (if applicable) -->
            @if(auth()->user()->hasRole('admin'))
            <div>
                <label for="franchisee_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.filters.franchisee') }}</label>
                <select id="franchisee_id" name="franchisee_id" 
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="">{{ __('ui.all') }}</option>
                    @foreach(\App\Models\Franchisee::orderBy('name')->get() as $franchisee)
                        <option value="{{ $franchisee->id }}" {{ request('franchisee_id') == $franchisee->id ? 'selected' : '' }}>
                            {{ $franchisee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        
        <div class="mt-4 flex justify-between">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('deployment.actions.filter') }}
            </button>
            
            <a href="{{ request()->url() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('deployment.actions.reset') }}
            </a>
            
            @if(request()->filled('truck_id') || request()->filled('status') || request()->filled('start_date') || request()->filled('end_date') || request()->filled('location') || request()->filled('franchisee_id'))
                <a href="{{ route('deployments.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    {{ __('deployment.actions.export') }}
                </a>
            @endif
        </div>
    </form>
</div>
