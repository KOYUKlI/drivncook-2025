@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Warehouse</h1>

<form action="{{ route('admin.warehouses.update', $warehouse) }}" method="POST" class="max-w-lg">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Warehouse Name</label>
        <input type="text" name="name" value="{{ old('name', $warehouse->name) }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Location</label>
        <input type="text" name="location" value="{{ old('location', $warehouse->location) }}" class="form-input">
        @error('location') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Franchise</label>
        <select name="franchise_id" class="form-select">
            @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" @selected(old('franchise_id', $warehouse->franchise_id) == $franchise->id)>
                    {{ $franchise->name }}
                </option>
            @endforeach
        </select>
        @error('franchise_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Update Warehouse</button>
    <a href="{{ route('admin.warehouses.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection