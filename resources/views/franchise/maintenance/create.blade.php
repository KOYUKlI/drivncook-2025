@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">New Maintenance Record</h1>

<form action="{{ route('franchise.maintenance.store') }}" method="POST" class="max-w-xl">
    @csrf
    <div class="form-group">
        <label class="form-label">Truck</label>
        <select name="truck_id" class="form-select">
            @foreach($trucks as $truck)
                <option value="{{ $truck->id }}">{{ $truck->name }}</option>
            @endforeach
        </select>
        @error('truck_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Date</label>
        <input type="date" name="maintenance_date" class="form-input">
        @error('maintenance_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-input" rows="3"></textarea>
        @error('description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Cost (€)</label>
        <input type="number" step="0.01" name="cost" class="form-input">
        @error('cost') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button class="btn-primary">Save</button>
        <a href="{{ route('franchise.maintenance.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection
