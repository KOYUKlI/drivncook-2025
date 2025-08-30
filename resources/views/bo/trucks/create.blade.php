@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.breadcrumbs :items="[
        ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.nav.trucks'),'url' => route('bo.trucks.index')],
        ['title' => __('ui.bo.trucks.create')]
    ]" />
<div class="max-w-6xl mx-auto p-6">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo_trucks.create.title') }}</h1>
            <p class="text-sm text-gray-600">{{ __('ui.bo.trucks.subtitle') }}</p>
        </div>
        <a href="{{ route('bo.trucks.index') }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 10-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/></svg>
            {{ __('ui.actions.back') }}
        </a>
    </div>

    @can('create', App\Models\Truck::class)
    <form action="{{ route('bo.trucks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- Section A: Identité -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a5 5 0 00-5 5v1H4a2 2 0 00-2 2v4a3 3 0 003 3h10a3 3 0 003-3v-4a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z"/></svg>
                <h2 class="text-lg font-medium text-gray-900">{{ __('ui.bo_trucks.sections.identity') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" required placeholder="Ex: DNC-Paris-01">
                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.plate_number') }}</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" required placeholder="AA-123-BB">
                    @error('plate_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.vin') }}</label>
                    <input type="text" name="vin" value="{{ old('vin') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="WVWZZZ...">
                    @error('vin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.make') }}</label>
                    <input type="text" name="make" value="{{ old('make') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Renault, Iveco…">
                    @error('make')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.model') }}</label>
                    <input type="text" name="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Master, Daily…">
                    @error('model')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.year') }}</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" min="1980" max="2100">
                    @error('year')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section B: Affectation -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm8 4a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h6z" clip-rule="evenodd"/></svg>
                <h2 class="text-lg font-medium text-gray-900">{{ __('ui.bo_trucks.sections.assignment') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.franchisee') }}</label>
                    <select name="franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        <option value="">{{ __('ui.common.unassigned') }}</option>
                        @foreach($franchisees as $f)
                            <option value="{{ $f->id }}" @selected(old('franchisee_id')===$f->id)>{{ $f->name }}</option>
                        @endforeach
                    </select>
                    @error('franchisee_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section C: État & dates -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h2a1 1 0 011 1v3H2V4a1 1 0 011-1zm-1 7h18v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5z" clip-rule="evenodd"/></svg>
                <h2 class="text-lg font-medium text-gray-900">{{ __('ui.bo_trucks.sections.status_dates') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.status') }}</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        <option value="draft" @selected(old('status')==='draft')>{{ __('ui.status.draft') }}</option>
                        <option value="active" @selected(old('status')==='active')>{{ __('ui.status.active') }}</option>
                        <option value="in_maintenance" @selected(old('status')==='in_maintenance')>{{ __('ui.status.in_maintenance') }}</option>
                        <option value="retired" @selected(old('status')==='retired')>{{ __('ui.status.retired') }}</option>
                    </select>
                    @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.acquired_at') }}</label>
                    <input type="date" name="acquired_at" value="{{ old('acquired_at') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                    @error('acquired_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.commissioned_at') }}</label>
                    <input type="date" name="commissioned_at" value="{{ old('commissioned_at') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                    @error('commissioned_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.mileage_km') }}</label>
                    <input type="number" name="mileage_km" value="{{ old('mileage_km') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" min="0">
                    @error('mileage_km')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section D: Documents privés -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                <h2 class="text-lg font-medium text-gray-900">{{ __('ui.bo_trucks.sections.documents') }}</h2>
            </div>
            <p class="text-sm text-gray-500 mb-4">{{ __('ui.help.files_private') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.registration_doc') }}</label>
                    <input type="file" name="registration_doc" class="mt-1 block w-full text-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <p class="text-xs text-gray-500 mt-1">{{ __('ui.help.upload_accepted_formats') }} · 10MB</p>
                    @error('registration_doc')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.insurance_doc') }}</label>
                    <input type="file" name="insurance_doc" class="mt-1 block w-full text-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <p class="text-xs text-gray-500 mt-1">{{ __('ui.help.upload_accepted_formats') }} · 10MB</p>
                    @error('insurance_doc')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section E: Notes -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h8l6-6V5a2 2 0 00-2-2H4z"/></svg>
                <h2 class="text-lg font-medium text-gray-900">{{ __('ui.bo_trucks.sections.notes') }}</h2>
            </div>
            <label class="block text-sm font-medium text-gray-700">{{ __('ui.bo_trucks.fields.notes') }}</label>
            <textarea name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="{{ __('ui.misc.optional') }}">{{ old('notes') }}</textarea>
            @error('notes')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('bo.trucks.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ __('ui.common.cancel') }}</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md shadow-sm">{{ __('ui.common.save') }}</button>
        </div>
    </form>
    @endcan
</div>
@endsection
