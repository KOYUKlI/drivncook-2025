@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
<x-ui.breadcrumbs :items="[
    ['title' => __('ui.fo.nav.dashboard'), 'url' => route('fo.dashboard')],
    ['title' => __('ui.fo.account.title')]
]" />
<div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h1 class="text-xl font-semibold text-gray-800">{{ __('ui.fo.account.title') }}</h1>
        <p class="text-gray-600 text-sm mt-1">{{ __('ui.fo.account.subtitle') }}</p>
    </div>
    
    <div class="p-6">
        @if (session('status') === 'account-updated')
            <x-ui.flash type="success" :message="__('ui.fo.account.messages.updated')" />
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <div class="font-medium">{{ __('ui.flash.validation_error') }}</div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('fo.account.update') }}" class="space-y-6">
            @csrf
            @method('patch')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.labels.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" disabled readonly class="bg-gray-100 border border-gray-300 text-gray-600 rounded-md w-full px-3 py-2 cursor-not-allowed">
                    <p class="mt-1 text-sm text-gray-500">{{ __('ui.fo.account.notes.email_managed_by_bo') }}</p>
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.fo.account.fields.phone') }}</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                           placeholder="{{ __('ui.fo.account.placeholders.phone') }}"
                           class="form-input">
                </div>
                
                <!-- Locale -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.fo.account.fields.locale') }}</label>
                    <select name="locale" id="locale" class="form-select">
                        <option value="fr" {{ old('locale', $user->locale) === 'fr' ? 'selected' : '' }}>{{ __('ui.fo.account.locale_options.fr') }}</option>
                        <option value="en" {{ old('locale', $user->locale) === 'en' ? 'selected' : '' }}>{{ __('ui.fo.account.locale_options.en') }}</option>
                    </select>
                </div>
                
                <!-- Email Notifications -->
                <div class="flex items-center">
                    <input type="hidden" name="notification_email_optin" value="0">
                    <input type="checkbox" name="notification_email_optin" id="notification_email_optin" value="1" {{ old('notification_email_optin', $user->notification_email_optin) ? 'checked' : '' }} class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded">
                    <label for="notification_email_optin" class="ml-2 block text-sm text-gray-700">
                        {{ __('ui.fo.account.fields.notification_email_optin') }}
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    {{ __('ui.fo.account.actions.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
