@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">My Trucks</h1>
    <a href="{{ route('franchise.trucks.create') }}" class="btn-primary">Add Truck</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>License Plate</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($trucks as $truck)
                <tr>
                    <td>{{ $truck->name }}</td>
                    <td>{{ $truck->license_plate }}</td>
                    <td class="text-center space-x-2">
                        <a href="{{ route('franchise.trucks.show', ['truck' => $truck]) }}" class="btn-link">View</a>
                        <a href="{{ route('franchise.trucks.edit', ['truck' => $truck]) }}" class="btn-link">Edit</a>
                        <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal', 'delete-truck-{{ $truck->id }}')">Delete</button>
                        <x-confirm-delete :name="'delete-truck-' . $truck->id"
                            :action="route('franchise.trucks.destroy', ['truck' => $truck])"
                            title="Delete Truck"
                            :message="'Delete ' . $truck->name . '?'" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection