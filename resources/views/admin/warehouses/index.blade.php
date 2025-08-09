@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">All Warehouses</h1>
    <a href="{{ route('admin.warehouses.create') }}" class="btn-primary">Add Warehouse</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Franchise</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($warehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->location }}</td>
                    <td>{{ $warehouse->franchise->name }}</td>
                    <td class="text-center space-x-2">
                        <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="btn-link">View</a>
                        <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="btn-link">Edit</a>
                        <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Confirm delete?')" class="btn-link text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection