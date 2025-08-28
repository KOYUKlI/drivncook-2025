@extends('layouts.app')

@section('content')
<x-container>
    <h1 class="text-2xl font-semibold mb-2">{{ __('ui.application_tracking') }}</h1>
    <p class="text-gray-600 mb-6">#{{ $application->id }}</p>
    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><dt class="font-medium">{{ __('ui.full_name') }}</dt><dd>{{ $application->full_name }}</dd></div>
        <div><dt class="font-medium">{{ __('ui.email') }}</dt><dd>{{ $application->email }}</dd></div>
        <div><dt class="font-medium">{{ __('ui.phone') }}</dt><dd>{{ $application->phone }}</dd></div>
        <div><dt class="font-medium">{{ __('ui.territory') }}</dt><dd>{{ $application->desired_area }}</dd></div>
        <div><dt class="font-medium">{{ __('ui.status') }}</dt><dd><span class="px-2 py-1 bg-gray-100 rounded">{{ $application->status }}</span></dd></div>
        <div><dt class="font-medium">{{ __('ui.submitted_at') }}</dt><dd>{{ $application->created_at->format('d/m/Y') }}</dd></div>
    </dl>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-semibold mb-3">Documents</h2>
            <ul class="list-disc pl-5 space-y-2">
                @forelse($application->documents as $doc)
                    <li>
                        <a class="text-orange-600 hover:underline" href="{{ Storage::disk('public')->url($doc->path) }}" target="_blank">
                            {{ ucfirst($doc->kind) }}
                        </a>
                        <span class="text-gray-500 text-sm">— {{ $doc->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500 text-sm">Aucun document.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-3">Timeline</h2>
            <ol class="relative border-l border-gray-200">
                @forelse($application->events as $event)
                    <li class="mb-6 ml-4">
                        <div class="absolute w-3 h-3 bg-orange-400 rounded-full -left-1.5 border border-white"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400">{{ $event->created_at->format('d/m/Y H:i') }}</time>
                        <p class="text-sm text-gray-700">{{ $event->from_status }} → <strong>{{ $event->to_status }}</strong></p>
                        @if($event->message)
                            <p class="text-xs text-gray-500">{{ $event->message }}</p>
                        @endif
                    </li>
                @empty
                    <li class="ml-4 text-gray-500 text-sm">Aucun événement.</li>
                @endforelse
            </ol>
        </div>
    </div>
</x-container>
@endsection
