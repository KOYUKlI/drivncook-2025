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
        <label class="form-label">Select Warehouse (or Supplier below)</label>
        <select name="warehouse_id" class="form-select" id="warehouse_id">
            <option value="">-- Choose Warehouse --</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
            @endforeach
        </select>
        @error('warehouse_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Or select a Supplier</label>
        <select name="supplier_id" class="form-select" id="supplier_id">
            <option value="">-- Choose Supplier --</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
        @error('supplier_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <p class="text-sm text-gray-600 mb-2">Astuce: choisissez <strong>soit</strong> un entrepôt <strong>soit</strong> un fournisseur.</p>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const w = document.getElementById('warehouse_id');
            const s = document.getElementById('supplier_id');
            function sync(){
                if (w.value) { s.value = ''; }
                if (s.value) { w.value = ''; }
            }
            w.addEventListener('change', sync);
            s.addEventListener('change', sync);
        });
    </script>
    <!-- Future enhancement: form inputs for items/quantities could be added here -->
    <button type="submit" class="btn-primary">Place Order</button>
    <a href="{{ route('franchise.stockorders.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection