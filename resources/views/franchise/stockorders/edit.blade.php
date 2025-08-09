@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Stock Order #{{ $stockOrder->id }}</h1>

@if($stockOrder->status !== 'pending')
    <p class="text-red-600">This order cannot be edited because it is already "{{ $stockOrder->status }}".</p>
@else
    <form action="{{ route('franchise.stockorders.update', $stockOrder) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Truck</label>
            <select name="truck_id" class="form-select">
                @foreach($trucks as $truck)
                    <option value="{{ $truck->id }}" @selected(old('truck_id', $stockOrder->truck_id) == $truck->id)>{{ $truck->name }}</option>
                @endforeach
            </select>
            @error('truck_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Warehouse</label>
            <select name="warehouse_id" class="form-select">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $stockOrder->warehouse_id) == $warehouse->id)>{{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('warehouse_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-primary">Update Order</button>
        <a href="{{ route('franchise.stockorders.index') }}" class="btn-link ml-3">Cancel</a>
    </form>
@endif
@endsection