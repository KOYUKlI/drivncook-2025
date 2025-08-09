@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Add New Warehouse</h1>

<form action="{{ route('admin.warehouses.store') }}" method="POST" class="max-w-lg">
    @csrf
    <div class="form-group">
        <label class="form-label">Warehouse Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Location</label>
        <input type="text" name="location" value="{{ old('location') }}" class="form-input">
        @error('location') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Franchise</label>
        <select name="franchise_id" class="form-select">
            <option value="">-- Select Franchise --</option>
            @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" @selected(old('franchise_id') == $franchise->id)>
                    {{ $franchise->name }}
                </option>
            @endforeach
        </select>
        @error('franchise_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Create Warehouse</button>
    <a href="{{ route('admin.warehouses.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection