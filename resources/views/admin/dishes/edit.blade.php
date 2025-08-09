@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Edit Dish</h1>
  <a href="{{ route('admin.dishes.index') }}" class="btn">Back</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="card p-4">
    <form method="POST" action="{{ route('admin.dishes.update', $dish) }}" class="space-y-3">
      @csrf @method('PUT')
      <div>
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-input" value="{{ old('name',$dish->name) }}" required />
      </div>
      <div>
        <label class="form-label">Description</label>
        <textarea name="description" class="form-textarea" rows="3">{{ old('description',$dish->description) }}</textarea>
      </div>
      <div>
        <label class="form-label">Price (€)</label>
        <input type="number" name="price" min="0" step="0.01" class="form-input w-40" value="{{ old('price',$dish->price) }}" required />
      </div>
      <div>
        <button class="btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="card p-4">
    <h2 class="text-lg font-semibold mb-2">Ingredients</h2>
    <form method="POST" action="{{ route('admin.dishes.ingredients.store', $dish) }}" class="flex items-end gap-3">
      @csrf
      <div>
        <label class="form-label">Supply</label>
        <select name="supply_id" class="form-select w-64">
          @foreach(\App\Models\Supply::orderBy('name')->get() as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="form-label">Qty per dish</label>
        <input type="number" name="qty_per_dish" step="0.001" min="0.000" value="0.000" class="form-input w-32" />
      </div>
      <div>
        <label class="form-label">Unit</label>
        <input type="text" name="unit" class="form-input w-24" placeholder="auto" />
      </div>
      <button class="btn-secondary">Add</button>
    </form>
    <ul class="mt-4 space-y-1">
      @forelse($dish->ingredients as $ing)
        <li class="flex items-center justify-between">
          <span>{{ $ing->qty_per_dish }} {{ $ing->unit }} — {{ $ing->supply->name }}</span>
          <span>
            <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal','remove-ing-{{ $ing->id }}')">Remove</button>
            <x-confirm-delete :name="'remove-ing-' . $ing->id" :action="route('admin.dishes.ingredients.destroy', [$dish, $ing])" title="Remove ingredient" :message="'Remove ' . $ing->supply->name . '?'" />
          </span>
        </li>
      @empty
        <li class="text-gray-600">No ingredients yet.</li>
      @endforelse
    </ul>
  </div>
</div>
@endsection
