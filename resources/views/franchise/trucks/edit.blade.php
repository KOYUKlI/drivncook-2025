@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Truck</h1>

<form action="{{ route('franchise.trucks.update', $truck) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Truck Name</label>
        <input type="text" name="name" value="{{ old('name', $truck->name) }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">License Plate</label>
        <input type="text" name="license_plate" value="{{ old('license_plate', $truck->license_plate) }}" class="form-input">
        @error('license_plate') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Update Truck</button>
    <a href="{{ route('franchise.trucks.index') }}" class="btn-link ml-3">Cancel</a>
</form>
@endsection