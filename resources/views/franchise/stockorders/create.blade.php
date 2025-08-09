@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">New Stock Order</h1>

<form action="{{ route('franchise.stockorders.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="form-group">
        <label class="form-label">Select Truck</label>
        <select name="truck_id" class="form-select">
            <option value="">-- Choose Truck --</option>
            @foreach($trucks as $truck)
                <option value="{{ $truck->id }}" @selected(old('truck_id') == $truck->id)>{{ $truck->name }}</option>
            @endforeach
        </select>
        @error('truck_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Select Warehouse</label>
        <select name="warehouse_id" class="form-select">
            <option value="">-- Choose Warehouse --</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
            @endforeach
        </select>
        @error('warehouse_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <!-- Future enhancement: form inputs for items/quantities could be added here -->
    <button type="submit" class="btn-primary">Place Order</button>
    <a href="{{ route('franchise.stockorders.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection