@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Supply</h1>

<form action="{{ route('admin.supplies.update', $supply) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Supply Name</label>
        <input type="text" name="name" value="{{ old('name', $supply->name) }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Unit</label>
        <input type="text" name="unit" value="{{ old('unit', $supply->unit) }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('unit') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Cost</label>
        <input type="number" step="0.01" name="cost" value="{{ old('cost', $supply->cost) }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('cost') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Supply</button>
    <a href="{{ route('admin.supplies.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection