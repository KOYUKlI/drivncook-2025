@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Truck</h1>

<form action="{{ route('franchise.trucks.update', $truck) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Truck Name</label>
        <input type="text" name="name" value="{{ old('name', $truck->name) }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">License Plate</label>
        <input type="text" name="license_plate" value="{{ old('license_plate', $truck->license_plate) }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('license_plate') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Truck</button>
    <a href="{{ route('franchise.trucks.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection