@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Edit Franchise</h1>

<form action="{{ route('admin.franchisees.update', $franchise) }}" method="POST" class="max-w-md">
    @csrf @method('PUT')
    <div class="form-group">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $franchise->name) }}">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save</button>
        <a href="{{ route('admin.franchisees.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection
