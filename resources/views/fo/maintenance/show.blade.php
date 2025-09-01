@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.labels.maintenance'))

@section('content')
<div class="py-6">
    <div class="mb-4">
        <a href="{{ route('fo.maintenance.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← {{ __('ui.common.back') }}</a>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $maintenanceLog->title ?? __('ui.labels.maintenance') }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $maintenanceLog->kind ?? '' }} · {{ $maintenanceLog->truck->code ?? $maintenanceLog->truck->plate ?? '' }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium border {{ $maintenanceLog->status_color }}">
                        {{ __('ui.maintenance.status.' . strtolower($maintenanceLog->status)) }}
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700">{{ __('ui.labels.description') }}</h3>
                    <p class="text-gray-800">{{ $maintenanceLog->description ?: __('ui.misc.not_provided') }}</p>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">{{ __('ui.maintenance.fields.opened_at') }}</div>
                        <div class="text-gray-900">{{ optional($maintenanceLog->opened_at ?? $maintenanceLog->started_at)->format('d/m/Y H:i') ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">{{ __('ui.maintenance.fields.closed_at') }}</div>
                        <div class="text-gray-900">{{ optional($maintenanceLog->closed_at)->format('d/m/Y H:i') ?: '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.labels.attachments') }}</h2>
                @if($maintenanceLog->attachments->isEmpty())
                    <p class="text-sm text-gray-500">{{ __('ui.empty.no_data') }}</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($maintenanceLog->attachments as $att)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="text-gray-900">{{ $att->label ?: basename($att->path) }}</div>
                                    <div class="text-xs text-gray-500">{{ $att->mime_type }} · {{ $att->formatted_size }}</div>
                                </div>
                                <a href="{{ route('fo.maintenance.attachment', [$maintenanceLog, $att]) }}" class="text-orange-600 hover:text-orange-800 text-sm">{{ __('ui.download') }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                <h3 class="text-base font-semibold text-gray-900">{{ __('ui.labels.truck') }}</h3>
                <dl class="mt-3 grid grid-cols-1 gap-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">{{ __('ui.labels.plate') }}</dt>
                        <dd class="text-gray-900">{{ $maintenanceLog->truck->plate ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">{{ __('ui.labels.status') }}</dt>
                        <dd class="text-gray-900">{{ $maintenanceLog->status }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
