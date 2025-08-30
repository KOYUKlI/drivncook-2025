<!-- Close Deployment Modal -->
@props(['deployment'])

<div id="close-deployment-{{ $deployment->id }}" class="modal fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="modal-overlay flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('bo.deployments.close', $deployment->id) }}" method="POST">
                @csrf
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('deployment.close_deployment') }}
                            </h3>
                            
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('deployment.close_deployment_info') }}
                                </p>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                @if(!$deployment->actual_start_at)
                                <!-- Actual Start Date (if not already set) -->
                                <div>
                                    <label for="actual_start_at_close_{{ $deployment->id }}" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.actual_start_at') }} *</label>
                                    <input type="datetime-local" name="actual_start_at" id="actual_start_at_close_{{ $deployment->id }}" 
                                        value="{{ $deployment->planned_start_at ? $deployment->planned_start_at->format('Y-m-d\TH:i') : now()->subDay()->format('Y-m-d\TH:i') }}" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @endif
                                
                                <!-- Actual End Date -->
                                <div>
                                    <label for="actual_end_at_{{ $deployment->id }}" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.actual_end_at') }} *</label>
                                    <input type="datetime-local" name="actual_end_at" id="actual_end_at_{{ $deployment->id }}" 
                                        value="{{ now()->format('Y-m-d\TH:i') }}" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                
                                <!-- Notes -->
                                <div>
                                    <label for="notes_close_{{ $deployment->id }}" class="block text-sm font-medium text-gray-700">{{ __('deployment.fields.notes') }}</label>
                                    <textarea name="notes" id="notes_close_{{ $deployment->id }}" rows="3" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $deployment->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('deployment.actions.close') }}
                    </button>
                    <button type="button" onclick="closeModal('close-deployment-{{ $deployment->id }}')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('ui.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
