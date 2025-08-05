@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">All Supplies</h1>

<a href="{{ route('admin.supplies.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Supply</a>

@if(session('success'))
    <div class="mt-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white mt-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="text-left py-2 px-4">Name</th>
            <th class="text-left py-2 px-4">Unit</th>
            <th class="text-left py-2 px-4">Cost</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($supplies as $supply)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $supply->name }}</td>
            <td class="py-2 px-4">{{ $supply->unit }}</td>
            <td class="py-2 px-4">${{ number_format($supply->cost, 2) }}</td>
            <td class="py-2 px-4 text-center">
                <a href="{{ route('admin.supplies.show', $supply) }}" class="text-blue-600 hover:underline">View</a> |
                <a href="{{ route('admin.supplies.edit', $supply) }}" class="text-yellow-600 hover:underline">Edit</a> |
                <form action="{{ route('admin.supplies.destroy', $supply) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Confirm delete?')" class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    @if($supplies->isEmpty())
        <tr><td colspan="4" class="py-4 px-4 text-center text-gray-600">No supplies found.</td></tr>
    @endif
    </tbody>
</table>
@endsection