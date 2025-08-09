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
                        <a href="{{ route('franchise.trucks.show', $truck) }}" class="btn-link">View</a>
                        <a href="{{ route('franchise.trucks.edit', $truck) }}" class="btn-link">Edit</a>
                        <form action="{{ route('franchise.trucks.destroy', $truck) }}" method="POST" class="inline">
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