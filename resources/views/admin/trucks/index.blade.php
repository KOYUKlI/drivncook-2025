@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">All Trucks</h1>

<a href="{{ route('admin.trucks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Truck</a>

@if(session('success'))
    <div class="mt-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white mt-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="text-left py-2 px-4">Name</th>
            <th class="text-left py-2 px-4">License Plate</th>
            <th class="text-left py-2 px-4">Franchise</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($trucks as $truck)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $truck->name }}</td>
            <td class="py-2 px-4">{{ $truck->license_plate }}</td>
            <td class="py-2 px-4">{{ $truck->franchise->name }}</td>
            <td class="py-2 px-4 text-center">
                <a href="{{ route('admin.trucks.show', $truck) }}" class="text-blue-600 hover:underline">View</a> |
                <a href="{{ route('admin.trucks.edit', $truck) }}" class="text-yellow-600 hover:underline">Edit</a> |
                <form action="{{ route('admin.trucks.destroy', $truck) }}" method="POST" class="inline">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Confirm delete?')" class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
