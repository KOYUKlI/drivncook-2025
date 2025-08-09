@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Add New Truck</h1>

<form action="{{ route('franchise.trucks.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="form-group">
        <label class="form-label">Truck Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">License Plate (optional)</label>
        <input type="text" name="license_plate" value="{{ old('license_plate') }}" class="form-input">
        @error('license_plate') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Add Truck</button>
    <a href="{{ route('franchise.trucks.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection