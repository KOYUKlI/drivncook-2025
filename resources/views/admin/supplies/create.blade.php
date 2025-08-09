@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Add New Supply</h1>

<form action="{{ route('admin.supplies.store') }}" method="POST" class="max-w-xl">
    @csrf
    <div class="form-group">
        <label class="form-label">Supply Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">SKU (référence)</label>
        <input type="text" name="sku" value="{{ old('sku') }}" class="form-input" placeholder="ex: BUN-001" />
        @error('sku') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Unit</label>
        <select name="unit" class="form-select">
            <option value="">-- Select unit --</option>
            @foreach(['kg','g','L','ml','pc','pack'] as $u)
                <option value="{{ $u }}" @selected(old('unit')===$u)>{{ $u }}</option>
            @endforeach
        </select>
        @error('unit') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Cost (per unit)</label>
        <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost') }}" class="form-input" placeholder="0.00">
        @error('cost') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create Supply</button>
        <a href="{{ route('admin.supplies.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection