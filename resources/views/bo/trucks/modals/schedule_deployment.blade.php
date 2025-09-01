<!-- Schedule Deployment Modal -->
@props(['truck'])

<div id="schedule-deployment-modal" class="modal fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="modal-overlay flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('bo.deployments.schedule', $truck) }}" method="POST">
                @csrf
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('deployment.schedule_deployment') }}
                            </h3>
                            
                            <div class="mt-4 space-y-4">
                                <!-- Location -->
                                <div>
                                    <label for="location_text" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.location') }} *</label>
                                    <input type="text" name="location_text" id="location_text" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                
                                <!-- Geo Coordinates (Optional) -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="geo_lat" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.geo_lat') }}</label>
                                        <input type="number" step="0.0000001" min="-90" max="90" name="geo_lat" id="geo_lat" 
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label for="geo_lng" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.geo_lng') }}</label>
                                        <input type="number" step="0.0000001" min="-180" max="180" name="geo_lng" id="geo_lng" 
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Planned Dates -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="planned_start_at" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.planned_start_at') }} *</label>
                                        <input type="datetime-local" name="planned_start_at" id="planned_start_at" required
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label for="planned_end_at" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.planned_end_at') }} *</label>
                                        <input type="datetime-local" name="planned_end_at" id="planned_end_at" required
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Franchisee -->
                                <div>
                                    <label for="franchisee_id" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.franchisee') }}</label>
                                    <select name="franchisee_id" id="franchisee_id" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">{{ __('ui.none') }}</option>
                                        @foreach(\App\Models\Franchisee::orderBy('name')->get() as $franchisee)
                                            <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.notes') }}</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('deployment.actions.schedule') }}
                    </button>
                    <button type="button" onclick="closeModal('schedule-deployment-modal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('ui.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
