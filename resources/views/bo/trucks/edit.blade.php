@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @can('update', $truck)
                    <form action="{{ route('bo.trucks.update', $truck->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Identity Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('ui.bo_trucks.sections.identity') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('ui.bo_trucks.fields.name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                        :value="old('name', $truck->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="plate_number" :value="__('ui.bo_trucks.fields.plate_number')" />
                                    <x-text-input id="plate_number" name="plate_number" type="text" class="mt-1 block w-full" 
                                        :value="old('plate_number', $truck->plate)" required />
                                    <x-input-error :messages="$errors->get('plate_number')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="vin" :value="__('ui.bo_trucks.fields.vin')" />
                                    <x-text-input id="vin" name="vin" type="text" class="mt-1 block w-full" 
                                        :value="old('vin', $truck->vin)" />
                                    <x-input-error :messages="$errors->get('vin')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="make" :value="__('ui.bo_trucks.fields.make')" />
                                    <x-text-input id="make" name="make" type="text" class="mt-1 block w-full" 
                                        :value="old('make', $truck->make)" />
                                    <x-input-error :messages="$errors->get('make')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="model" :value="__('ui.bo_trucks.fields.model')" />
                                    <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" 
                                        :value="old('model', $truck->model)" />
                                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="year" :value="__('ui.bo_trucks.fields.year')" />
                                    <x-text-input id="year" name="year" type="number" min="1980" max="2100" class="mt-1 block w-full" 
                                        :value="old('year', $truck->year)" />
                                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('ui.bo_trucks.sections.assignment') }}</h3>
                            <div>
                                <x-input-label for="franchisee_id" :value="__('ui.bo_trucks.fields.franchisee')" />
                                <select id="franchisee_id" name="franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('ui.common.unassigned') }}</option>
                                    @foreach($franchisees as $franchisee)
                                        <option value="{{ $franchisee->id }}" {{ old('franchisee_id', $truck->franchisee_id) == $franchisee->id ? 'selected' : '' }}>
                                            {{ $franchisee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('franchisee_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Status & Counters Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('ui.bo_trucks.sections.status_dates') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="status" :value="__('ui.bo_trucks.fields.status')" />
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="draft" {{ old('status', $truck->ui_status) == 'draft' ? 'selected' : '' }}>
                                            {{ __('ui.status.draft') }}
                                        </option>
                                        <option value="active" {{ old('status', $truck->ui_status) == 'active' ? 'selected' : '' }}>
                                            {{ __('ui.status.active') }}
                                        </option>
                                        <option value="in_maintenance" {{ old('status', $truck->ui_status) == 'in_maintenance' ? 'selected' : '' }}>
                                            {{ __('ui.status.in_maintenance') }}
                                        </option>
                                        <option value="retired" {{ old('status', $truck->ui_status) == 'retired' ? 'selected' : '' }}>
                                            {{ __('ui.status.retired') }}
                                        </option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="mileage_km" :value="__('ui.bo_trucks.fields.mileage_km')" />
                                    <x-text-input id="mileage_km" name="mileage_km" type="number" min="0" class="mt-1 block w-full" 
                                        :value="old('mileage_km', $truck->mileage_km)" />
                                    <x-input-error :messages="$errors->get('mileage_km')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">
                                {{ __('ui.bo_trucks.sections.documents') }}
                                <span class="text-sm font-normal text-gray-500">({{ __('ui.help.files_private') }})</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="registration_doc" :value="__('ui.bo_trucks.fields.registration_doc')" />
                                    <input id="registration_doc" name="registration_doc" type="file" class="mt-1 block w-full" />
                                    @if($truck->registration_doc_path)
                                        <div class="mt-2 text-sm">
                                            <a href="{{ route('bo.trucks.files.download', ['truck' => $truck->id, 'type' => 'registration']) }}" 
                                               class="text-blue-600 hover:underline">
                                                {{ __('ui.actions.download') }} {{ __('ui.bo_trucks.fields.registration_doc') }}
                                            </a>
                                        </div>
                                    @endif
                                    <x-input-error :messages="$errors->get('registration_doc')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="insurance_doc" :value="__('ui.bo_trucks.fields.insurance_doc')" />
                                    <input id="insurance_doc" name="insurance_doc" type="file" class="mt-1 block w-full" />
                                    @if($truck->insurance_doc_path)
                                        <div class="mt-2 text-sm">
                                            <a href="{{ route('bo.trucks.files.download', ['truck' => $truck->id, 'type' => 'insurance']) }}" 
                                               class="text-blue-600 hover:underline">
                                                {{ __('ui.actions.download') }} {{ __('ui.bo_trucks.fields.insurance_doc') }}
                                            </a>
                                        </div>
                                    @endif
                                    <x-input-error :messages="$errors->get('insurance_doc')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('ui.bo_trucks.sections.notes') }}</h3>
                            <div>
                                <x-input-label for="notes" :value="__('ui.bo_trucks.fields.notes')" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $truck->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <a href="{{ route('bo.trucks.show', $truck->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('ui.actions.cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('ui.actions.save') }}
                            </button>
                        </div>
                    </form>
                    @else
                        <div class="text-red-500">
                            {{ __('ui.flash.unauthorized') }}
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
