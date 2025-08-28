@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.applications'), 'url' => route('bo.applications.index')],
        ['title' => $application['name']]
    ]" />

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.application') }} - {{ $application['name'] }}</h1>
                <p class="text-gray-600">{{ $application['territory'] }} - {{ __('ui.submitted_on') }} {{ \Carbon\Carbon::parse($application['submitted_at'])->format('d/m/Y') }}</p>
            </div>
            
            @php
            $statusColors = [
                'in_review' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'pending' => 'bg-gray-100 text-gray-800'
            ];
            @endphp
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$application['status']] }}">
                {{ __('ui.' . $application['status']) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: Application info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Applicant Information -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.applicant_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.full_name') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $application['name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.email') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $application['email'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.phone') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $application['phone'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.territory') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $application['territory'] }}</dd>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.documents') }}</h3>
                <div class="space-y-3">
                    @foreach($application['documents'] as $document)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-900">{{ $document['name'] }}</span>
                        </div>
                        @php
                        $docStatusColors = [
                            'uploaded' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'rejected' => 'bg-red-100 text-red-800'
                        ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $docStatusColors[$document['status']] }}">
                            {{ __('ui.' . $document['status']) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right column: Workflow -->
        <div>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.application_workflow') }}</h3>
                <div class="space-y-4">
                    @foreach($application['workflow_steps'] as $step)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($step['status'] === 'completed')
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @elseif($step['status'] === 'in_progress')
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ __('ui.' . $step['step']) }}</div>
                            @if($step['date'])
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($step['date'])->format('d/m/Y') }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3">
                        @if($application['status'] === 'in_review')
                            <form method="POST" action="{{ route('bo.applications.prequalify', $application['id']) }}">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    {{ __('ui.prequalify') }}
                                </button>
                            </form>
                        @elseif($application['status'] === 'prequalified')
                            <form method="POST" action="{{ route('bo.applications.interview', $application['id']) }}">
                                @csrf
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    {{ __('ui.schedule_interview') }}
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($application['status'], ['prequalified', 'interview_scheduled']))
                            <form method="POST" action="{{ route('bo.applications.approve', $application['id']) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir approuver cette candidature ? Un franchisé sera créé automatiquement.')">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    {{ __('ui.approve_application') }}
                                </button>
                            </form>
                        @endif
                        
                        @if(!in_array($application['status'], ['approved', 'rejected']))
                            <form method="POST" action="{{ route('bo.applications.reject', $application['id']) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette candidature ?')">
                                @csrf
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    {{ __('ui.reject_application') }}
                                </button>
                            </form>
                        @endif
                        
                        <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            {{ __('ui.send_message') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
