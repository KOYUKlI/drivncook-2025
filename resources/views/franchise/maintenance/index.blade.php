@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Maintenance Records</h1>
    <a href="{{ route('franchise.maintenance.create') }}" class="btn-primary">Add Record</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Truck</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr>
                        <td>{{ $rec->maintenance_date }}</td>
                        <td>{{ $rec->truck->name }}</td>
                        <td>{{ Str::limit($rec->description, 60) }}</td>
                        <td>{{ number_format($rec->cost ?? 0, 2) }} €</td>
                        <td class="text-center">
                            <a href="{{ route('franchise.maintenance.edit', $rec) }}" class="btn-link">Edit</a>
                            <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal', 'delete-maint-{{ $rec->id }}')">Delete</button>
                            <x-confirm-delete :name="'delete-maint-' . $rec->id"
                                :action="route('franchise.maintenance.destroy', $rec)"
                                title="Delete maintenance record"
                                :message="'Delete record #' . $rec->id . '?'" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-gray-600">No records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
