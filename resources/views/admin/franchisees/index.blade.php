@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Franchisees</h1>
    <a href="{{ route('admin.franchisees.create') }}" class="btn-primary">Add Franchise</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Trucks</th>
                    <th>Warehouses</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($franchises as $franchise)
                    <tr>
                        <td>{{ $franchise->name }}</td>
                        <td>{{ $franchise->trucks()->count() }}</td>
                        <td>{{ $franchise->warehouses()->count() }}</td>
                        <td class="text-center space-x-2">
                            <a href="{{ route('admin.franchisees.show', $franchise) }}" class="btn-link">View</a>
                            <a href="{{ route('admin.franchisees.edit', $franchise) }}" class="btn-link">Edit</a>
                            <form action="{{ route('admin.franchisees.destroy', $franchise) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-link text-red-600" onclick="return confirm('Confirm delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
