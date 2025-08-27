@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.applications')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.applications') }}</h1>
        <p class="text-gray-600">{{ __('ui.manage_franchise_applications') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.applicant') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.email') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.territory') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.current_step') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.submitted_date') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($applications as $application)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $application['name'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $application['email'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $application['territory'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'in_review' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'pending' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$application['status']] }}">
                                {{ __('ui.' . $application['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $stepColors = [
                                'application' => 'bg-blue-100 text-blue-800',
                                'documents' => 'bg-orange-100 text-orange-800',
                                'interview' => 'bg-purple-100 text-purple-800',
                                'contract' => 'bg-green-100 text-green-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $stepColors[$application['step']] }}">
                                {{ __('ui.' . $application['step']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($application['submitted_at'])->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('bo.applications.show', $application['id']) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('ui.review') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
