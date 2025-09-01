@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.truck.title'))

@section('content')
<x-ui.breadcrumbs :items="[
    ['title' => __('ui.fo.nav.dashboard'), 'url' => route('fo.dashboard')],
    ['title' => __('ui.fo.nav.fo_truck'), 'url' => route('fo.truck.show')],
]" />

<div class="py-4">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.truck.title') }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="tabs mb-4">
        <a class="tab tab-lifted tab-active" id="tab-info" onclick="showTab('info')">{{ __('ui.fo.truck.tabs.info') }}</a>
        <a class="tab tab-lifted" id="tab-deployments" onclick="showTab('deployments')">{{ __('ui.fo.truck.tabs.deployments') }}</a>
        <a class="tab tab-lifted" id="tab-maintenance" onclick="showTab('maintenance')">{{ __('ui.fo.truck.tabs.maintenance') }}</a>
    </div>

    <!-- Truck Info Tab -->
    <div id="content-info" class="tab-content">
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title">{{ __('ui.fo.truck.sections.details') }}</h2>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <tbody>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.name') }}</th>
                                <td>{{ $truck->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.plate_number') }}</th>
                                <td>{{ $truck->plate_number }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.make') }}</th>
                                <td>{{ $truck->make }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.model') }}</th>
                                <td>{{ $truck->model }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.year') }}</th>
                                <td>{{ $truck->year }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.status') }}</th>
                                <td>
                                    <span class="badge 
                                        @if($truck->status == 'active') badge-success
                                        @elseif($truck->status == 'in_maintenance') badge-warning
                                        @elseif($truck->status == 'inactive') badge-error
                                        @else badge-ghost @endif
                                    ">
                                        {{ __('ui.status.' . $truck->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.commissioned_at') }}</th>
                                <td>{{ $truck->commissioned_at ? $truck->commissioned_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('ui.fo.truck.fields.mileage_km') }}</th>
                                <td>{{ $truck->mileage_km ?? '-' }} km</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Deployments Tab -->
    <div id="content-deployments" class="tab-content hidden">
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title">{{ __('ui.fo.truck.sections.upcoming_deployments') }}</h2>
                <div class="overflow-x-auto">
                    @if($upcomingDeployments->count() > 0)
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>{{ __('ui.fo.truck.fields.location') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.planned_start') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.planned_end') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingDeployments as $deployment)
                                    <tr>
                                        <td>{{ $deployment->location }}</td>
                                        <td>{{ $deployment->planned_start_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $deployment->planned_end_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($deployment->status == 'planned') badge-info
                                                @elseif($deployment->status == 'open') badge-success
                                                @elseif($deployment->status == 'closed') badge-ghost
                                                @elseif($deployment->status == 'cancelled') badge-error
                                                @endif
                                            ">
                                                {{ __('ui.deployment.status.' . $deployment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ __('ui.fo.truck.messages.no_upcoming_deployments') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Tab -->
    <div id="content-maintenance" class="tab-content hidden">
        @can('requestMaintenance', $truck)
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    <h2 class="card-title">{{ __('ui.fo.maintenance_request.title') }}</h2>
                    <form action="{{ route('fo.truck.maintenance-request') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">{{ __('ui.fo.maintenance_request.fields.title') }} *</span>
                            </label>
                            <input type="text" name="title" class="input input-bordered @error('title') input-error @enderror" 
                                placeholder="{{ __('ui.fo.maintenance_request.placeholders.title') }}" 
                                value="{{ old('title') }}" required>
                            @error('title')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">{{ __('ui.fo.maintenance_request.fields.type') }} *</span>
                            </label>
                            <select name="type" class="select select-bordered @error('type') select-error @enderror" required>
                                <option value="">{{ __('ui.fo.maintenance_request.placeholders.select_type') }}</option>
                                <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>{{ __('ui.maintenance.type.preventive') }}</option>
                                <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>{{ __('ui.maintenance.type.corrective') }}</option>
                            </select>
                            @error('type')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">{{ __('ui.fo.maintenance_request.fields.description') }} *</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered @error('description') textarea-error @enderror" 
                                placeholder="{{ __('ui.fo.maintenance_request.placeholders.description') }}" 
                                rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">{{ __('ui.fo.maintenance_request.fields.attachment') }}</span>
                                <span class="label-text-alt">{{ __('ui.fo.maintenance_request.notes.attachment_formats') }}</span>
                            </label>
                            <input type="file" name="attachment" class="file-input file-input-bordered @error('attachment') file-input-error @enderror">
                            @error('attachment')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control">
                            <button type="submit" class="btn btn-primary">{{ __('ui.fo.maintenance_request.actions.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title">{{ __('ui.fo.truck.sections.maintenance_history') }}</h2>
                <div class="overflow-x-auto">
                    @if($maintenanceLogs->count() > 0)
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>{{ __('ui.fo.truck.fields.maintenance_title') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.maintenance_type') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.created_at') }}</th>
                                    <th>{{ __('ui.fo.truck.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenanceLogs as $log)
                                    <tr>
                                        <td>{{ $log->title }}</td>
                                        <td>{{ __('ui.maintenance.type.' . $log->type) }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($log->status == 'planned') badge-info
                                                @elseif($log->status == 'open') badge-warning
                                                @elseif($log->status == 'closed') badge-success
                                                @elseif($log->status == 'cancelled') badge-error
                                                @endif
                                            ">
                                                {{ __('ui.maintenance.status.' . $log->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ __('ui.fo.truck.messages.no_maintenance_history') }}</span>
                            </div>
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
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('tab-active');
    });
    
    // Show the selected tab content and activate the tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    document.getElementById('tab-' + tabName).classList.add('tab-active');
}
</script>
@endsection
