@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Stock Order</h1>

<form action="{{ route('franchise.stockorders.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Select Truck</label>
        <select name="truck_id" class="mt-1 block w-full border-gray-300 rounded">
            <option value="">-- Choose Truck --</option>
            @foreach($trucks as $truck)
                <option value="{{ $truck->id }}" @selected(old('truck_id') == $truck->id)>{{ $truck->name }}</option>
            @endforeach
        </select>
        @error('truck_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label class="block font-medium text-gray-700">Select Warehouse</label>
        <select name="warehouse_id" class="mt-1 block w-full border-gray-300 rounded">
            <option value="">-- Choose Warehouse --</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
            @endforeach
        </select>
        @error('warehouse_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <!-- Future enhancement: form inputs for items/quantities could be added here -->
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Place Order</button>
    <a href="{{ route('franchise.stockorders.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection