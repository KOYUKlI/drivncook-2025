@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Truck Details</h1>

<div class="bg-white p-6 rounded shadow max-w-md">
    <p><strong>Name:</strong> {{ $truck->name }}</p>
    <p><strong>License Plate:</strong> {{ $truck->license_plate ?? 'N/A' }}</p>
</div>

<a href="{{ route('franchise.trucks.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to My Trucks</a>
@endsection