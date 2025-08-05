@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Stock Order #{{ $stockOrder->id }}</h1>

@if($stockOrder->status !== 'pending')
    <p class="text-red-600">This order cannot be edited because it is already "{{ $stockOrder->status }}".</p>
@else
    <form action="{{ route('franchise.stockorders.update', $stockOrder) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-medium text-gray-700">Truck</label>
            <select name="truck_id" class="mt-1 block w-full border-gray-300 rounded">
                @foreach($trucks as $truck)
                    <option value="{{ $truck->id }}" @selected(old('truck_id', $stockOrder->truck_id) == $truck->id)>{{ $truck->name }}</option>
                @endforeach
            </select>
            @error('truck_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium text-gray-700">Warehouse</label>
            <select name="warehouse_id" class="mt-1 block w-full border-gray-300 rounded">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $stockOrder->warehouse_id) == $warehouse->id)>{{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('warehouse_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Order</button>
        <a href="{{ route('franchise.stockorders.index') }}" class="ml-4 text-gray-600">Cancel</a>
    </form>
@endif
@endsection