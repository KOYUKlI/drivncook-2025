@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.truck.title'))

@section('content')
<x-ui.breadcrumbs :items="[
    ['title' => __('ui.fo.nav.dashboard'), 'url' => route('fo.dashboard')],
    ['title' => __('ui.fo.truck.title'), 'url' => route('fo.truck.show')],
]" />

<div class="py-4">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.truck.title') }}</h1>
    </div>

    <x-ui.flash type="success" />
    <x-ui.flash type="error" />

    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex gap-6" aria-label="Tabs">
            <button id="tab-info" onclick="showTab('info')" class="whitespace-nowrap border-b-2 border-orange-600 px-1 py-2 text-sm font-medium text-orange-600">
                {{ __('ui.fo.truck.tabs.info') }}
            </button>
            <button id="tab-deployments" onclick="showTab('deployments')" class="whitespace-nowrap border-b-2 border-transparent px-1 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                {{ __('ui.fo.truck.tabs.deployments') }}
            </button>
            <button id="tab-maintenance" onclick="showTab('maintenance')" class="whitespace-nowrap border-b-2 border-transparent px-1 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                {{ __('ui.fo.truck.tabs.maintenance') }}
            </button>
        </nav>
    </div>

    <!-- Truck Info Tab -->
    <div id="content-info" class="tab-content">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 md:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.fo.truck.sections.details') }}</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.name') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->name }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.plate_number') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->plate_number }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.make') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->make }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.model') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->model }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.year') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->year }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.status') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'in_maintenance' => 'bg-yellow-100 text-yellow-800',
                                            'inactive' => 'bg-red-100 text-red-800',
                                            'default' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $class = $statusClasses[$truck->status] ?? $statusClasses['default'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                        {{ __('ui.status.' . $truck->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.commissioned_at') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->commissioned_at ? $truck->commissioned_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-500">{{ __('ui.fo.truck.fields.mileage_km') }}</th>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $truck->mileage_km ?? '-' }} km</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Deployments Tab -->
    <div id="content-deployments" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 md:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.fo.truck.sections.upcoming_deployments') }}</h2>
                <div class="overflow-x-auto">
                    @if($upcomingDeployments->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.location') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.planned_start') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.planned_end') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($upcomingDeployments as $deployment)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $deployment->location }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $deployment->planned_start_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $deployment->planned_end_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            @php
                                                $dStatus = [
                                                    'planned' => 'bg-blue-100 text-blue-800',
                                                    'open' => 'bg-green-100 text-green-800',
                                                    'closed' => 'bg-gray-100 text-gray-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ][$deployment->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dStatus }}">
                                                {{ __('ui.deployment.status.' . $deployment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4 text-blue-800">
                            <p class="text-sm">{{ __('ui.fo.truck.messages.no_upcoming_deployments') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Tab -->
    <div id="content-maintenance" class="tab-content hidden">
        @can('requestMaintenance', $truck)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="p-4 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.fo.maintenance_request.title') }}</h2>
                    <form action="{{ route('fo.truck.maintenance-request') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.maintenance_request.fields.title') }} *</label>
                            <input type="text" name="title" class="form-input w-full @error('title') border-red-500 @enderror" 
                                placeholder="{{ __('ui.fo.maintenance_request.placeholders.title') }}" 
                                value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.maintenance_request.fields.type') }} *</label>
                            <select name="type" class="form-select w-full @error('type') border-red-500 @enderror" required>
                                <option value="">{{ __('ui.fo.maintenance_request.placeholders.select_type') }}</option>
                                <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>{{ __('ui.maintenance.type.preventive') }}</option>
                                <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>{{ __('ui.maintenance.type.corrective') }}</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.maintenance_request.fields.description') }} *</label>
                            <textarea name="description" class="form-input w-full h-28 @error('description') border-red-500 @enderror" 
                                placeholder="{{ __('ui.fo.maintenance_request.placeholders.description') }}" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.maintenance_request.fields.attachment') }}</label>
                                <span class="text-xs text-gray-500">{{ __('ui.fo.maintenance_request.notes.attachment_formats') }}</span>
                            </div>
                            <input type="file" name="attachment" class="block w-full text-sm text-gray-700 border rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 {{ $errors->has('attachment') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('attachment')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="btn-primary">{{ __('ui.fo.maintenance_request.actions.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 md:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.fo.truck.sections.maintenance_history') }}</h2>
                <div class="overflow-x-auto">
                    @if($maintenanceLogs->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.maintenance_title') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.maintenance_type') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.created_at') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.truck.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($maintenanceLogs as $log)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $log->title }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ __('ui.maintenance.type.' . $log->type) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $log->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            @php
                                                $mStatus = [
                                                    'planned' => 'bg-blue-100 text-blue-800',
                                                    'open' => 'bg-yellow-100 text-yellow-800',
                                                    'closed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ][$log->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mStatus }}">
                                                {{ __('ui.maintenance.status.' . $log->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4 text-blue-800">
                            <p class="text-sm">{{ __('ui.fo.truck.messages.no_maintenance_history') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Reset all tabs to inactive styles
    const inactive = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
    const active = 'border-orange-600 text-orange-600';
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('border-b-2', 'border-orange-600', 'text-orange-600');
        tab.classList.add('border-b-2');
        tab.classList.add(...inactive.split(' '));
        // remove inactive classes if present before adding active later
        tab.classList.remove(...active.split(' '));
    });

    // Show the selected tab content and activate the tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const btn = document.getElementById('tab-' + tabName);
    btn.classList.remove(...inactive.split(' '));
    btn.classList.add('border-b-2');
    btn.classList.add(...active.split(' '));
}
</script>
@endsection
