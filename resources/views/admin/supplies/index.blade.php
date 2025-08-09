@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Supplies</h1>
    <a href="{{ route('admin.supplies.create') }}" class="btn-primary">Add Supply</a>
  </div>

<div class="card">
  <div class="card-body p-0">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Cost</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($supplies as $supply)
            <tr>
                <td>{{ $supply->name }}</td>
                <td>{{ $supply->unit }}</td>
                <td>${{ number_format($supply->cost, 2) }}</td>
                <td class="text-center">
                    <div class="inline-flex items-center gap-3">
                        <a href="{{ route('admin.supplies.show', $supply) }}" class="btn-link">View</a>
                        <a href="{{ route('admin.supplies.edit', $supply) }}" class="text-amber-600 hover:underline">Edit</a>
                        <button type="button" class="text-red-600 hover:underline"
                            x-data
                            x-on:click="$dispatch('open-modal', 'delete-supply-{{ $supply->id }}')">Delete</button>
                        <x-confirm-delete :name="'delete-supply-' . $supply->id"
                            :action="route('admin.supplies.destroy', $supply)"
                            title="Delete supply"
                            :message="'Are you sure you want to delete ' . $supply->name . '?'" />
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-600">No supplies found.</td></tr>
        @endforelse
        </tbody>
    </table>
  </div>
</div>
@endsection