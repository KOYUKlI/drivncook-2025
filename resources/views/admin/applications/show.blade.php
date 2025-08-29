<x-app-layout>
    <x-slot name="title">{{ __('ui.bo.applications.detail_title') }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.applications.detail_title') }}</h1>
                <p class="text-gray-600">{{ $application->full_name }} • {{ $application->email }}</p>
            </div>
            <a href="{{ route('bo.applications.index') }}" class="text-orange-600 hover:text-orange-800">
                ← {{ __('ui.actions.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Application Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.bo.applications.application_info') }}</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.full_name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.desired_area') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->desired_area }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.created_at') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.status') }}</dt>
                            <dd class="mt-1">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'prequalified' => 'bg-yellow-100 text-yellow-800',
                                        'interview' => 'bg-purple-100 text-purple-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ __('ui.status.' . $application->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Documents -->
                @if($application->documents->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.labels.documents') }}</h3>
                        <div class="space-y-3">
                            @foreach($application->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ __('ui.labels.' . ($document->kind ?? 'document')) }}</p>
                                            <p class="text-xs text-gray-500">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @can('view', $application)
                                        <a href="{{ route('bo.applications.files.download', $document) }}" 
                                           class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                            {{ __('ui.actions.download') }}
                                        </a>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('ui.bo.applications.timeline') }}</h3>
                    
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($application->events as $index => $event)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900">
                                                        {{ __('ui.bo.applications.events.status_changed') }}
                                                        ({{ __('ui.status.' . ($event->from_status ?? '')) }} → {{ __('ui.status.' . ($event->to_status ?? '')) }})
                                                    </p>
                                                    @if($event->message)
                                                        <p class="mt-1 text-sm text-gray-600">{{ $event->message }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $event->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Status Actions -->
                @can('update', $application)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.bo.applications.actions') }}</h3>
                        
                        @if(count($availableTransitions) > 0)
                            <div class="space-y-3">
                                @foreach($availableTransitions as $transition)
                                    <form method="POST" action="{{ route('bo.applications.update-status', $application) }}" class="w-full">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $transition }}">
                                        
                                        @php
                                            $buttonColors = [
                                                'prequalified' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
                                                'interview' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500',
                                                'approved' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
                                                'rejected' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                                            ];
                                        @endphp
                                        
                                        <div class="space-y-2">
                                            <button type="submit" 
                                                    class="w-full px-4 py-2 text-white text-sm font-medium rounded-md {{ $buttonColors[$transition] ?? 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2"
                                                    onclick="return confirm('{{ __('ui.bo.applications.confirm_status_change', ['status' => __('ui.status.' . $transition)]) }}')">
                                                {{ __('ui.bo.applications.action_' . $transition) }}
                                            </button>
                                            
                                            @if($transition === 'rejected')
                                                <textarea name="comment" 
                                                         placeholder="{{ __('ui.bo.applications.comment_placeholder') }}"
                                                         class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                                         rows="2"></textarea>
                                            @else
                                                <input type="text" 
                                                       name="comment" 
                                                       placeholder="{{ __('ui.bo.applications.comment_placeholder') }}"
                                                       class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                            @endif
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">{{ __('ui.bo.applications.no_actions') }}</p>
                        @endif
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
