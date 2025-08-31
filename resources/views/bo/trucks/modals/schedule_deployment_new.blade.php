<!-- New S            quickTimes: [
            { label: '{{ __('ui.times.morning') }}', start: '08:00', end: '12:00' },
            { label: '{{ __('ui.times.afternoon') }}', start: '14:00', end: '18:00' },
            { label: '{{ __('ui.times.evening') }}', start: '18:00', end: '22:00' },
            { label: '{{ __('ui.times.all_day') }}', start: '08:00', end: '22:00' }
        ],e Deployment Modal -->
@props(['truck'])

<div 
    id="schedule-deployment-modal" 
    class="fixed inset-0 z-50 overflow-y-auto hidden" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    x-data="{
        quickTimes: [
            { label: '{{ __('deploiement.times.morning') }}', start: '08:00', end: '12:00' },
            { label: '{{ __('deploiement.times.afternoon') }}', start: '14:00', end: '18:00' },
            { label: '{{ __('deploiement.times.evening') }}', start: '18:00', end: '22:00' },
            { label: '{{ __('deploiement.times.all_day') }}', start: '08:00', end: '22:00' }
        ],
        locations: [
            'Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Toulouse', 'Nice', 'Strasbourg'
        ],
        startDate: '',
        endDate: '',
        startTime: '',
        endTime: '',
        isSameDay: true,
        
        init() {
            // Set default values on modal open
            this.$watch('startDate', (value) => {
                if (this.isSameDay) {
                    this.endDate = value;
                }
                this.updateFormValues();
            });
            
            this.$watch('endDate', () => this.updateFormValues());
            this.$watch('startTime', () => this.updateFormValues());
            this.$watch('endTime', () => this.updateFormValues());
            
            // Initialize with tomorrow's date and default times
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            this.startDate = tomorrow.toISOString().split('T')[0];
            this.endDate = this.startDate;
            this.startTime = '09:00';
            this.endTime = '18:00';
            this.updateFormValues();
        },
        
        updateFormValues() {
            if (this.startDate && this.startTime) {
                this.$refs.plannedStartAt.value = `${this.startDate}T${this.startTime}`;
            }
            
            if (this.endDate && this.endTime) {
                this.$refs.plannedEndAt.value = `${this.endDate}T${this.endTime}`;
            }
        },
        
        setQuickTime(start, end) {
            this.startTime = start;
            this.endTime = end;
            this.updateFormValues();
        }
    }"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            class="fixed inset-0 bg-gray-700 bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            @click="document.getElementById('schedule-deployment-modal').classList.add('hidden')"
        ></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button 
                    type="button"
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                    @click="document.getElementById('schedule-deployment-modal').classList.add('hidden')"
                >
                    <span class="sr-only">{{ __('deploiement.close') }}</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="bg-gradient-to-b from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-full">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white" id="modal-title">
                        {{ __('deployment.schedule_deployment') }}
                    </h3>
                </div>
                <p class="mt-1 text-sm text-white text-opacity-90">
                    {{ __('deployment.schedule_deployment') }} <span class="font-semibold">{{ $truck->code ?? $truck->plate }}</span>
                </p>
            </div>

            <form action="{{ route('bo.deployments.schedule', $truck) }}" method="POST" class="bg-white">
                @csrf
                
                <div class="px-6 py-4">
                    <div class="space-y-5">
                        <!-- Location with suggestions -->
                        <div>
                            <label for="location_text" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.location') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="location_text" 
                                    id="location_text" 
                                    required
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="{{ __('deploiement.deployment.placeholder.location') }}"
                                    list="location-suggestions"
                                >
                                <datalist id="location-suggestions">
                                    <template x-for="loc in locations" :key="loc">
                                        <option x-text="loc"></option>
                                    </template>
                                </datalist>
                            </div>
                        </div>
                        
                        <!-- Quick time selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.quick_select') }}</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="time in quickTimes" :key="time.label">
                                    <button 
                                        type="button"
                                        class="px-3 py-1 text-sm rounded-full border border-gray-300 hover:bg-orange-50 hover:border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                        x-text="time.label"
                                        @click="setQuickTime(time.start, time.end)"
                                    ></button>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Date and time selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.start_date') }} <span class="text-red-500">*</span></label>
                                <input 
                                    type="date" 
                                    x-model="startDate"
                                    min="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.end_date') }} <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <input 
                                            type="date" 
                                            x-model="endDate"
                                            :min="startDate"
                                            :disabled="isSameDay"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 disabled:bg-gray-100 disabled:text-gray-500"
                                        >
                                    </div>
                                    <div class="flex items-center" title="{{ __('deploiement.deployment.same_day') }}">
                                        <input 
                                            type="checkbox" 
                                            id="same_day" 
                                            x-model="isSameDay"
                                            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                        >
                                        <label for="same_day" class="ml-2 text-xs text-gray-700">{{ __('deploiement.deployment.same_day') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.start_time') }} <span class="text-red-500">*</span></label>
                                <input 
                                    type="time" 
                                    x-model="startTime"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.end_time') }} <span class="text-red-500">*</span></label>
                                <input 
                                    type="time" 
                                    x-model="endTime"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                            </div>
                        </div>

                        <!-- Hidden datetime fields for form submission -->
                        <input 
                            type="hidden" 
                            name="planned_start_at" 
                            x-ref="plannedStartAt"
                            required
                        >
                        <input 
                            type="hidden" 
                            name="planned_end_at" 
                            x-ref="plannedEndAt"
                            required
                        >
                        
                        <!-- Franchisee (if applicable) -->
                        @if(auth()->user()->hasRole('admin'))
                        <div>
                            <label for="franchisee_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.franchisee') }}</label>
                            <select 
                                name="franchisee_id" 
                                id="franchisee_id" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                            >
                                <option value="">{{ __('ui.none') }}</option>
                                @foreach(\App\Models\Franchisee::orderBy('name')->get() as $franchisee)
                                    <option value="{{ $franchisee->id }}" {{ $truck->franchisee_id == $franchisee->id ? 'selected' : '' }}>
                                        {{ $franchisee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="franchisee_id" value="{{ $truck->franchisee_id }}">
                        @endif
                        
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deploiement.deployment.fields.notes') }}</label>
                            <textarea 
                                name="notes" 
                                id="notes" 
                                rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="{{ __('deploiement.deployment.notes_placeholder') }}"
                            ></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <button 
                        type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                        @click="document.getElementById('schedule-deployment-modal').classList.add('hidden')"
                    >
                        {{ __('ui.actions.cancel') }}
                    </button>
                    
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                    >
                        <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('deployment.actions.schedule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to handle the modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add functions to window object so they can be called from HTML
        window.openDeploymentModal = function() {
            document.getElementById('schedule-deployment-modal').classList.remove('hidden');
        };
        
        window.closeDeploymentModal = function() {
            document.getElementById('schedule-deployment-modal').classList.add('hidden');
        };
    });
</script>
