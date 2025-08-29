<x-app-layout>
    <x-slot name="title">{{ __('ui.public.application.tracking_title') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Heading -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('ui.public.application.tracking_title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('ui.public.application.tracking_subtitle') }}</p>
        </div>

        <!-- Status card -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('ui.status.status') ?? __('ui.status_types.status') ?? 'Status' }}</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        @php
                            $statusColors = [
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'prequalified' => 'bg-yellow-100 text-yellow-800',
                                'interview' => 'bg-purple-100 text-purple-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $statusKey = $application->status;
                            $currentColor = $statusColors[$statusKey] ?? 'bg-gray-100 text-gray-800';
                            $statusLabel = __('ui.status.' . $statusKey);
                            if ($statusLabel === 'ui.status.' . $statusKey) {
                                $statusLabel = ucfirst($statusKey);
                            }
                        @endphp
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $currentColor }}">
                            {{ $statusLabel }}
                        </span>
                        <p class="text-gray-600 mt-2">
                            {{ __('ui.labels.updated_at') }} : {{ $application->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('ui.public.application.submitted_on') ?? __('ui.submitted_on') ?? '' }}</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $application->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Personal info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('ui.public.application.summary') }}
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">{{ __('ui.labels.full_name') }}</span>
                        <span class="text-gray-800 font-semibold">{{ $application->full_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">{{ __('ui.labels.email') }}</span>
                        <span class="text-gray-800">{{ $application->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">{{ __('ui.labels.phone') }}</span>
                        <span class="text-gray-800">{{ $application->phone }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">{{ __('ui.labels.desired_area') }}</span>
                        <span class="text-gray-800 font-semibold">{{ $application->desired_area }}</span>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('ui.public.application.documents') }}
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($application->documents as $doc)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ ucfirst($doc->kind) }}</p>
                                    <p class="text-sm text-gray-500">{{ $doc->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-gray-500">{{ __('ui.empty.no_data') }}</p>
                        </div>
                    @endforelse
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-700">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            {{ __('ui.notes.files_private') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('ui.public.application.timeline') }}
                </h2>
            </div>
            <div class="p-6">
                @forelse($application->events as $event)
                    <div class="relative pl-8 pb-8 last:pb-0">
                        <div class="absolute left-0 top-0 w-4 h-4 bg-orange-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="absolute left-2 top-4 w-px h-full bg-gray-200 last:hidden"></div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $event->from_status }} → <span class="text-orange-600">{{ $event->to_status }}</span>
                                </h3>
                                <time class="text-sm text-gray-500">{{ $event->created_at->format('d/m/Y H:i') }}</time>
                            </div>
                            @if($event->message)
                                <p class="text-gray-600 text-sm">{{ $event->message }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-gray-500">{{ __('ui.empty.no_data') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
