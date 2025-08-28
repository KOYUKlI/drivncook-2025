@extends('layouts.app')

@section('content')
<x-container>
    <h1 class="text-2xl font-semibold mb-4">{{ __('ui.apply_title') }}</h1>
    <form method="POST" action="{{ route('public.applications.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input-label for="first_name" :value="__('ui.first_name')" />
            <x-text-input id="first_name" name="first_name" value="{{ old('first_name') }}" required />

            <x-input-label for="last_name" :value="__('ui.last_name')" />
            <x-text-input id="last_name" name="last_name" value="{{ old('last_name') }}" required />

            <x-input-label for="email" :value="__('ui.email')" />
            <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" required />

            <x-input-label for="phone" :value="__('ui.phone')" />
            <x-text-input id="phone" name="phone" value="{{ old('phone') }}" required />

            <x-input-label for="territory" :value="__('ui.territory')" />
            <x-text-input id="territory" name="territory" value="{{ old('territory') }}" required />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="cv" :value="__('ui.cv')" />
                <input id="cv" name="cv" type="file" accept="application/pdf" />
                <x-input-error :messages="$errors->get('cv')" />
            </div>
            <div>
                <x-input-label for="identity" :value="__('ui.identity')" />
                <input id="identity" name="identity" type="file" accept="image/*" />
                <x-input-error :messages="$errors->get('identity')" />
            </div>
        </div>
        <x-primary-button>{{ __('ui.submit_application') }}</x-primary-button>
    </form>
</x-container>
@endsection
