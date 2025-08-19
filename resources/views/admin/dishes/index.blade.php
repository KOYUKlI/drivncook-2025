@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Dishes</h1>
  <a href="{{ route('admin.dishes.create') }}" class="btn-primary">Add Dish</a>
</div>
<div class="card p-0">
  <table class="data-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($dishes as $dish)
        <tr>
          <td>{{ $dish->name }}</td>
          <td>€ {{ number_format($dish->price, 2) }}</td>
          <td class="text-center space-x-2">
            <a href="{{ route('admin.dishes.show', $dish) }}" class="btn-link">View</a>
            <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn-link">Edit</a>
            <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal','delete-dish-{{ $dish->id }}')">Delete</button>
            <x-confirm-delete :name="'delete-dish-' . $dish->id" :action="route('admin.dishes.destroy', $dish)" title="Delete dish" :message="'Delete ' . $dish->name . '?'" />
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="px-4 py-6 text-center text-gray-600">No dishes found.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="p-3">{{ $dishes->links() }}</div>
</div>
@endsection
