@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Trucks</h1>
    <a href="{{ route('admin.trucks.create') }}" class="btn-primary">Add Truck</a>
  </div>

<div class="card">
  <div class="card-body p-0">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>License Plate</th>
                <th>Franchise</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($trucks as $truck)
            <tr>
                <td>{{ $truck->name }}</td>
                <td>{{ $truck->license_plate }}</td>
                <td>{{ $truck->franchise->name }}</td>
                <td class="text-center">
                    <div class="inline-flex items-center gap-3">
                        <a href="{{ route('admin.trucks.show', ['truck' => $truck]) }}" class="btn-link">View</a>
                        <a href="{{ route('admin.trucks.edit', ['truck' => $truck]) }}" class="text-amber-600 hover:underline">Edit</a>
                        <button type="button" class="text-red-600 hover:underline" x-data x-on:click="$dispatch('open-modal', 'delete-admin-truck-{{ $truck->id }}')">Delete</button>
                        <x-confirm-delete :name="'delete-admin-truck-' . $truck->id"
                            :action="route('admin.trucks.destroy', ['truck' => $truck])"
                            title="Delete truck"
                            :message="'Delete ' . $truck->name . '?'" />
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
  </div>
</div>
@endsection
