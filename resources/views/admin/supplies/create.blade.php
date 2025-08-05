@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add New Supply</h1>

<form action="{{ route('admin.supplies.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Supply Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Unit (e.g., kg, L, pack)</label>
        <input type="text" name="unit" value="{{ old('unit') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('unit') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Cost (per unit)</label>
        <input type="number" step="0.01" name="cost" value="{{ old('cost') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('cost') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Supply</button>
    <a href="{{ route('admin.supplies.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection