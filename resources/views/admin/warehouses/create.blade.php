@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add New Warehouse</h1>

<form action="{{ route('admin.warehouses.store') }}" method="POST" class="max-w-lg">
    @csrf
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Warehouse Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Location</label>
        <input type="text" name="location" value="{{ old('location') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('location') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Franchise</label>
        <select name="franchise_id" class="mt-1 block w-full border-gray-300 rounded">
            <option value="">-- Select Franchise --</option>
            @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" @selected(old('franchise_id') == $franchise->id)>
                    {{ $franchise->name }}
                </option>
            @endforeach
        </select>
        @error('franchise_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Warehouse</button>
    <a href="{{ route('admin.warehouses.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection