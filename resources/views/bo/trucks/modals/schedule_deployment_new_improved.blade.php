<!-- Schedule Deployment Modal - Improved Version -->
@props(['truck'])

<div id="schedule-deployment-modal" class="modal fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ step: 1, location: '', startDate: '', startTime: '', endDate: '', endTime: '', notes: '', franchiseeId: '{{ $truck->franchisee_id ?? '' }}' }">
    <div class="modal-overlay flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('bo.deployments.schedule', $truck) }}" method="POST" 
                  @submit.prevent="
                  if (step < 3) { 
                      step++; 
                  } else { 
                      $event.target.submit(); 
                  }">
                @csrf
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white" id="modal-title">
                            {{ __('deployment.schedule_deployment') }}
                        </h3>
                        <button type="button" @click="closeModal('schedule-deployment-modal')" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Progress Steps -->
                <div class="px-6 pt-4 bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div :class="{ 'bg-orange-500': step >= 1, 'bg-gray-200': step < 1 }" class="flex h-8 w-8 items-center justify-center rounded-full text-white transition-colors duration-200">
                                <svg x-show="step > 1" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-show="step <= 1">1</span>
                            </div>
                            <div :class="{ 'bg-orange-500': step >= 1, 'bg-gray-200': step < 1 }" class="h-1 w-10 transition-colors duration-200"></div>
                        </div>
                        <div class="flex items-center">
                            <div :class="{ 'bg-orange-500': step >= 2, 'bg-gray-200': step < 2 }" class="h-1 w-10 transition-colors duration-200"></div>
                            <div :class="{ 'bg-orange-500': step >= 2, 'bg-gray-200': step < 2 }" class="flex h-8 w-8 items-center justify-center rounded-full text-white transition-colors duration-200">
                                <svg x-show="step > 2" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-show="step <= 2">2</span>
                            </div>
                            <div :class="{ 'bg-orange-500': step >= 2, 'bg-gray-200': step < 2 }" class="h-1 w-10 transition-colors duration-200"></div>
                        </div>
                        <div class="flex items-center">
                            <div :class="{ 'bg-orange-500': step >= 3, 'bg-gray-200': step < 3 }" class="h-1 w-10 transition-colors duration-200"></div>
                            <div :class="{ 'bg-orange-500': step >= 3, 'bg-gray-200': step < 3 }" class="flex h-8 w-8 items-center justify-center rounded-full text-white transition-colors duration-200">
                                <svg x-show="step > 3" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-show="step <= 3">3</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-3 text-xs font-medium text-gray-500">
                        <span>{{ __('deployment.steps.location') }}</span>
                        <span>{{ __('deployment.steps.schedule') }}</span>
                        <span>{{ __('deployment.steps.review') }}</span>
                    </div>
                </div>
                
                <!-- Step 1: Location -->
                <div x-show="step === 1" class="px-6 py-6 bg-white">
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('deployment.steps.location_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('deployment.steps.location_desc') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="location_text" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.location') }} *</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="text" name="location_text" id="location_text" x-model="location" 
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="{{ __('deployment.placeholder.location') }}" required>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ __('deployment.help.location') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="franchisee_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.franchisee') }}</label>
                        <select name="franchisee_id" id="franchisee_id" x-model="franchiseeId"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">{{ __('ui.none') }}</option>
                            @foreach(\App\Models\Franchisee::orderBy('name')->get() as $franchisee)
                                <option value="{{ $franchisee->id }}" {{ $truck->franchisee_id == $franchisee->id ? 'selected' : '' }}>
                                    {{ $franchisee->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('deployment.help.franchisee') }}</p>
                    </div>
                </div>
                
                <!-- Step 2: Schedule -->
                <div x-show="step === 2" class="px-6 py-6 bg-white">
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('deployment.steps.schedule_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('deployment.steps.schedule_desc') }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.start_date') }} *</label>
                            <input type="date" id="start_date" x-model="startDate" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                        </div>
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.start_time') }} *</label>
                            <input type="time" id="start_time" x-model="startTime" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.end_date') }} *</label>
                            <input type="date" id="end_date" x-model="endDate" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.end_time') }} *</label>
                            <input type="time" id="end_time" x-model="endTime" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('deployment.fields.notes') }}</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <textarea name="notes" id="notes" x-model="notes" rows="3"
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="{{ __('deployment.placeholder.notes') }}"></textarea>
                        </div>
                    </div>
                    
                    <!-- Hidden fields to store the combined datetime values -->
                    <input type="hidden" name="planned_start_at" x-bind:value="startDate && startTime ? `${startDate}T${startTime}` : ''">
                    <input type="hidden" name="planned_end_at" x-bind:value="endDate && endTime ? `${endDate}T${endTime}` : ''">
                </div>
                
                <!-- Step 3: Review -->
                <div x-show="step === 3" class="px-6 py-6 bg-white">
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('deployment.steps.review_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('deployment.steps.review_desc') }}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center mb-3">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.truck') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900">{{ $truck->code ?? $truck->plate }}</div>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.location') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900" x-text="location || '—'"></div>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.franchisee') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900">
                                <span x-show="franchiseeId">
                                    @foreach(\App\Models\Franchisee::orderBy('name')->get() as $franchisee)
                                        <span x-show="franchiseeId === '{{ $franchisee->id }}'">{{ $franchisee->name }}</span>
                                    @endforeach
                                </span>
                                <span x-show="!franchiseeId">{{ __('ui.none') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.start') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900" x-text="startDate && startTime ? `${startDate} ${startTime}` : '—'"></div>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.end') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900" x-text="endDate && endTime ? `${endDate} ${endTime}` : '—'"></div>
                        </div>
                        <div class="flex items-start mb-1">
                            <div class="w-1/3 text-sm font-medium text-gray-500">{{ __('deployment.fields.notes') }}:</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900" x-text="notes || '{{ __('ui.none') }}'"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-800">{{ __('deployment.help.confirmation') }}</p>
                    </div>
                </div>
                
                <!-- Footer with Navigation Buttons -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                    <button type="button" x-show="step > 1" @click="step--" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('ui.actions.back') }}
                    </button>
                    
                    <div class="flex space-x-3">
                        <button type="button" @click="closeModal('schedule-deployment-modal')" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            {{ __('ui.actions.cancel') }}
                        </button>
                        
                        <button type="submit" x-show="step < 3"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            {{ __('ui.actions.continue') }}
                            <svg class="h-4 w-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        
                        <button type="submit" x-show="step === 3"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('deployment.actions.schedule') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    // Initialize with today's date for start and end
    const today = new Date().toISOString().split('T')[0];
    const defaultTime = '09:00';
    const endTime = '18:00';
    
    // Set default values when opening the modal
    window.openScheduleDeploymentModal = () => {
        const modal = document.getElementById('schedule-deployment-modal');
        if (modal) {
            // Get Alpine component
            const component = Alpine.findClosest(modal, el => el.__x);
            if (component) {
                component.$data.step = 1;
                component.$data.startDate = today;
                component.$data.startTime = defaultTime;
                component.$data.endDate = today;
                component.$data.endTime = endTime;
            }
            
            // Show the modal
            modal.classList.remove('hidden');
        }
    }
});
</script>
