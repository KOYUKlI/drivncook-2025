@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Supply</h1>

<form action="{{ route('admin.supplies.update', $supply) }}" method="POST" class="max-w-xl">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Supply Name</label>
        <input type="text" name="name" value="{{ old('name', $supply->name) }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Unit</label>
        <input type="text" name="unit" value="{{ old('unit', $supply->unit) }}" class="form-input">
        @error('unit') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Cost</label>
        <input type="number" step="0.01" name="cost" value="{{ old('cost', $supply->cost) }}" class="form-input">
        @error('cost') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Supply</button>
        <a href="{{ route('admin.supplies.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection