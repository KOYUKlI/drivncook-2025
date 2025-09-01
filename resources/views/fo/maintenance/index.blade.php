@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.labels.maintenance'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.labels.maintenance') }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-gray-900">{{ __('ui.maintenance.history') }}</h2>
                        <form method="GET" class="flex items-center gap-2">
                            <select name="status" class="form-select">
                                <option value="">{{ __('ui.misc.all_statuses') }}</option>
                                @foreach (['planned','open','paused','closed','cancelled'] as $s)
                                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ __('ui.maintenance.status.' . $s) }}</option>
                                @endforeach
                            </select>
                            <button class="btn-secondary">{{ __('ui.actions.filter') }}</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.date') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.description') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.status') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($logs as $log)
                                <tr>
                                    <td class="px-4 py-2">{{ optional($log->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-medium text-gray-900">{{ $log->title ?? ($log->kind ?? '—') }}</div>
                                        <div class="text-gray-600 text-sm line-clamp-2">{{ $log->description }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium border {{ $log->status_color }}">
                                            {{ __('ui.maintenance.status.' . strtolower($log->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('fo.maintenance.show', $log) }}" class="text-orange-600 hover:text-orange-800 inline-flex items-center gap-1">
                                            {{ __('ui.view') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('ui.empty.no_maintenance') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.maintenance_request.title') }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ __('ui.fo.maintenance_request.subtitle') }}</p>
                <a href="{{ route('fo.truck.show') }}#content-maintenance" class="btn-primary inline-flex items-center">
                    {{ __('ui.fo.maintenance_request.actions.submit') }}
                </a>
            </div>
        </div>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        <p>{{ __('ui.help.files_private') }}</p>
    </div>
</div>
@endsection
