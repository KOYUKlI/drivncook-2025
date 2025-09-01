@push('scripts')
<script>
    function showModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('block');
    }
    
    function hideModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('block');
        modal.classList.add('hidden');
    }
</script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('maintenance.maintenance_details') }}
            </h2>
            <div class="flex space-x-2">
                @if($maintenanceLog->status === \App\Models\MaintenanceLog::STATUS_PLANNED)
                    @can('update', $maintenanceLog)
                        <a href="{{ route('bo.maintenance.edit', $maintenanceLog) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                            {{ __('Edit') }}
                        </a>
                    @endcan
                    @can('open', $maintenanceLog)
                        <button type="button" onclick="showModal('openModal')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                            {{ __('maintenance.open') }}
                        </button>
                    @endcan
                    @can('cancel', $maintenanceLog)
                        <button type="button" onclick="showModal('cancelModal')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            {{ __('maintenance.cancel') }}
                        </button>
                    @endcan
                @endif
                
                @if($maintenanceLog->status === \App\Models\MaintenanceLog::STATUS_OPEN)
                    @can('pause', $maintenanceLog)
                        <button type="button" onclick="showModal('pauseModal')" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:border-purple-700 focus:ring focus:ring-purple-200 disabled:opacity-25 transition">
                            {{ __('maintenance.pause') }}
                        </button>
                    @endcan
                    @can('close', $maintenanceLog)
                        <button type="button" onclick="showModal('closeModal')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                            {{ __('maintenance.close') }}
                        </button>
                    @endcan
                    @can('cancel', $maintenanceLog)
                        <button type="button" onclick="showModal('cancelModal')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            {{ __('maintenance.cancel') }}
                        </button>
                    @endcan
                @endif
                
                @if($maintenanceLog->status === \App\Models\MaintenanceLog::STATUS_PAUSED)
                    @can('resume', $maintenanceLog)
                        <button type="button" onclick="showModal('resumeModal')" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 active:bg-amber-700 focus:outline-none focus:border-amber-700 focus:ring focus:ring-amber-200 disabled:opacity-25 transition">
                            {{ __('maintenance.resume') }}
                        </button>
                    @endcan
                    @can('close', $maintenanceLog)
                        <button type="button" onclick="showModal('closeModal')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                            {{ __('maintenance.close') }}
                        </button>
                    @endcan
                    @can('cancel', $maintenanceLog)
                        <button type="button" onclick="showModal('cancelModal')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            {{ __('maintenance.cancel') }}
                        </button>
                    @endcan
                @endif
                
                <a href="{{ route('bo.maintenance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.maintenance_details') }}</h3>
                            
                            <div class="mb-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $maintenanceLog->status_color }}">
                                    {{ __('maintenance.status_' . $maintenanceLog->status) }}
                                </span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $maintenanceLog->severity_color }}">
                                    {{ __('maintenance.severity_' . $maintenanceLog->severity) }}
                                </span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $maintenanceLog->priority_color }}">
                                    {{ __('maintenance.priority_' . $maintenanceLog->priority) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.truck') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->truck->identifier }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.title') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->title }}</p>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.description') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->description }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.planned_start_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->planned_start_at?->format('Y-m-d H:i') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.planned_end_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->planned_end_at?->format('Y-m-d H:i') }}</p>
                                </div>
                                
                                @if($maintenanceLog->opened_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.opened_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->opened_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->odometer_start)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.odometer_start') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->odometer_start) }} km</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->paused_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.paused_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->paused_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->pause_reason)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.pause_reason') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->pause_reason }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->resume_notes)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.resume_notes') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->resume_notes }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->closed_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.closed_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->closed_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->odometer_end)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.odometer_end') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->odometer_end) }} km</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->resolution)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.resolution') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->resolution }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->cancelled_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.cancelled_at') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->cancelled_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->cancellation_reason)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.cancellation_reason') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->cancellation_reason }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->duration)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.duration') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->duration }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-semibold mt-8 mb-4">{{ __('maintenance.provider_information') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($maintenanceLog->provider_name)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.provider_name') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->provider_name }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->provider_contact)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.provider_contact') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->provider_contact }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->provider_reference)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.provider_reference') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->provider_reference }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->provider_invoice_reference)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.provider_invoice_reference') }}</p>
                                    <p class="mt-1">{{ $maintenanceLog->provider_invoice_reference }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-semibold mt-8 mb-4">{{ __('maintenance.cost_information') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($maintenanceLog->estimated_cost_amount)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.estimated_cost_amount') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->estimated_cost_amount, 2) }} {{ $maintenanceLog->estimated_cost_currency }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->labor_cost_amount)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.labor_cost_amount') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->labor_cost_amount, 2) }} {{ $maintenanceLog->labor_cost_currency }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->parts_cost_amount)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.parts_cost_amount') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->parts_cost_amount, 2) }} {{ $maintenanceLog->parts_cost_currency }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->additional_costs_amount)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.additional_costs_amount') }}</p>
                                    <p class="mt-1">{{ number_format($maintenanceLog->additional_costs_amount, 2) }} {{ $maintenanceLog->additional_costs_currency }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->additional_costs_description)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">{{ __('maintenance.additional_costs_description') }}</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $maintenanceLog->additional_costs_description }}</p>
                                </div>
                                @endif
                                
                                @if($maintenanceLog->total_cost_amount)
                                <div class="md:col-span-2 mt-2">
                                    <p class="text-sm font-bold text-gray-700">{{ __('maintenance.total_cost') }}</p>
                                    <p class="mt-1 font-bold">{{ number_format($maintenanceLog->total_cost_amount, 2) }} {{ $maintenanceLog->total_cost_currency }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.attachments') }}</h3>
                                
                                @if($maintenanceLog->attachments->isEmpty())
                                    <p class="text-gray-500">{{ __('maintenance.no_attachments') }}</p>
                                @else
                                    <div class="space-y-4">
                                        @foreach($maintenanceLog->attachments as $attachment)
                                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                                <div>
                                                    <p class="font-medium">{{ $attachment->original_filename }}</p>
                                                    <div class="flex text-sm text-gray-500 mt-1">
                                                        <p>{{ $attachment->formatted_size }}</p>
                                                        <span class="mx-2">•</span>
                                                        <p>{{ $attachment->extension }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('bo.maintenance.attachment.download', $attachment) }}" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 transition">
                                                    {{ __('maintenance.download') }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @can('addAttachment', $maintenanceLog)
                                    <form action="{{ route('bo.maintenance.attachment.upload', $maintenanceLog) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        <div class="flex items-center">
                                            <input type="file" name="attachments[]" id="attachments" multiple class="form-control">
                                            <button type="submit" class="ml-2 inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                                {{ __('maintenance.add_attachment') }}
                                            </button>
                                        </div>
                                    </form>
                                @endcan
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold mb-4">{{ __('Activity') }}</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.created_by') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->created_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($maintenanceLog->opened_at)
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.opened_at') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->opened_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($maintenanceLog->paused_at)
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.paused_at') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->paused_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($maintenanceLog->resumed_at)
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.resumed_at') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->resumed_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($maintenanceLog->closed_at)
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.closed_at') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->closed_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($maintenanceLog->cancelled_at)
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm">
                                                <span class="font-medium">{{ __('maintenance.cancelled_at') }}</span>
                                            </p>
                                            <p class="text-gray-500 text-sm">{{ $maintenanceLog->cancelled_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Open Modal -->
    <div id="openModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-auto mt-32">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.open') }} {{ __('maintenance.maintenance') }}</h3>
                <form action="{{ route('bo.maintenance.open', $maintenanceLog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="odometer_reading" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.odometer_reading') }}</label>
                        <input type="number" name="odometer_reading" id="odometer_reading" class="w-full rounded-md border-gray-300" required>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.notes') }}</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.attachments') }}</label>
                        <input type="file" name="attachments[]" id="attachments" multiple class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal('openModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            {{ __('maintenance.open') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Pause Modal -->
    <div id="pauseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-auto mt-32">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.pause') }} {{ __('maintenance.maintenance') }}</h3>
                <form action="{{ route('bo.maintenance.pause', $maintenanceLog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="pause_reason" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.pause_reason') }}</label>
                        <textarea name="pause_reason" id="pause_reason" rows="3" class="w-full rounded-md border-gray-300" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.attachments') }}</label>
                        <input type="file" name="attachments[]" id="attachments" multiple class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal('pauseModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                            {{ __('maintenance.pause') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Resume Modal -->
    <div id="resumeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-auto mt-32">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.resume') }} {{ __('maintenance.maintenance') }}</h3>
                <form action="{{ route('bo.maintenance.resume', $maintenanceLog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="resume_notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.resume_notes') }}</label>
                        <textarea name="resume_notes" id="resume_notes" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.attachments') }}</label>
                        <input type="file" name="attachments[]" id="attachments" multiple class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal('resumeModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                            {{ __('maintenance.resume') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Close Modal -->
    <div id="closeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-auto mt-32">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.close') }} {{ __('maintenance.maintenance') }}</h3>
                <form action="{{ route('bo.maintenance.close.enhanced', $maintenanceLog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="resolution" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.resolution') }}</label>
                        <textarea name="resolution" id="resolution" rows="3" class="w-full rounded-md border-gray-300" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="odometer_reading" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.odometer_reading') }}</label>
                        <input type="number" name="odometer_reading" id="odometer_reading" class="w-full rounded-md border-gray-300" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="labor_cost_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.labor_cost_amount') }}</label>
                            <input type="number" step="0.01" name="labor_cost_amount" id="labor_cost_amount" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label for="labor_cost_currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.labor_cost_currency') }}</label>
                            <input type="text" name="labor_cost_currency" id="labor_cost_currency" class="w-full rounded-md border-gray-300" value="EUR" maxlength="3">
                        </div>
                        <div>
                            <label for="parts_cost_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.parts_cost_amount') }}</label>
                            <input type="number" step="0.01" name="parts_cost_amount" id="parts_cost_amount" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label for="parts_cost_currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.parts_cost_currency') }}</label>
                            <input type="text" name="parts_cost_currency" id="parts_cost_currency" class="w-full rounded-md border-gray-300" value="EUR" maxlength="3">
                        </div>
                        <div>
                            <label for="additional_costs_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.additional_costs_amount') }}</label>
                            <input type="number" step="0.01" name="additional_costs_amount" id="additional_costs_amount" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label for="additional_costs_currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.additional_costs_currency') }}</label>
                            <input type="text" name="additional_costs_currency" id="additional_costs_currency" class="w-full rounded-md border-gray-300" value="EUR" maxlength="3">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="additional_costs_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.additional_costs_description') }}</label>
                        <textarea name="additional_costs_description" id="additional_costs_description" rows="2" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="provider_invoice_reference" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.provider_invoice_reference') }}</label>
                        <input type="text" name="provider_invoice_reference" id="provider_invoice_reference" class="w-full rounded-md border-gray-300">
                    </div>
                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.attachments') }}</label>
                        <input type="file" name="attachments[]" id="attachments" multiple class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal('closeModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            {{ __('maintenance.close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-auto mt-32">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('maintenance.cancel') }} {{ __('maintenance.maintenance') }}</h3>
                <form action="{{ route('bo.maintenance.cancel', $maintenanceLog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.cancellation_reason') }}</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="w-full rounded-md border-gray-300" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">{{ __('maintenance.attachments') }}</label>
                        <input type="file" name="attachments[]" id="attachments" multiple class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal('cancelModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            {{ __('maintenance.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
