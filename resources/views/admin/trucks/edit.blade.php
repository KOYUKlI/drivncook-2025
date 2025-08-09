@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Truck</h1>

<form action="{{ route('admin.trucks.update', $truck) }}" method="POST" class="max-w-xl">
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
    <div class="form-group">
        <label class="form-label">Franchise</label>
        <select name="franchise_id" class="form-select">
            @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" @selected(old('franchise_id', $truck->franchise_id) == $franchise->id)>
                    {{ $franchise->name }}
                </option>
            @endforeach
        </select>
        @error('franchise_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Truck</button>
        <a href="{{ route('admin.trucks.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection