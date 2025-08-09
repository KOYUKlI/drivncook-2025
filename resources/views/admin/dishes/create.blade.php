@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">New Dish</h1>
  <a href="{{ route('admin.dishes.index') }}" class="btn">Back</a>
</div>
<div class="card p-4 max-w-2xl">
  <form method="POST" action="{{ route('admin.dishes.store') }}" class="space-y-3">
    @csrf
    <div>
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-input" required />
      @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>
    <div>
      <label class="form-label">Description</label>
      <textarea name="description" class="form-textarea" rows="3"></textarea>
    </div>
    <div>
      <label class="form-label">Price (€)</label>
      <input type="number" name="price" min="0" step="0.01" class="form-input w-40" required />
      @error('price') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>
    <div>
      <button class="btn-primary">Create</button>
    </div>
  </form>
</div>
@endsection
