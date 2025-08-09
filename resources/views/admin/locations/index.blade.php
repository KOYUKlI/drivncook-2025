@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Locations</h1>
  <a href="{{ route('admin.locations.create') }}" class="btn-primary">Add</a>
</div>
<div class="card"><div class="card-body p-0">
<table class="data-table">
  <thead><tr><th>Label</th><th>City</th><th>Coords</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($locations as $loc)
      <tr>
        <td>{{ $loc->label }}</td>
        <td>{{ $loc->city }}</td>
        <td>{{ $loc->lat }}, {{ $loc->lng }}</td>
        <td class="text-center space-x-2">
          <a class="btn-link" href="{{ route('admin.locations.show', $loc) }}">View</a>
          <a class="btn-link" href="{{ route('admin.locations.edit', $loc) }}">Edit</a>
          <form action="{{ route('admin.locations.destroy', $loc) }}" method="POST" class="inline">@csrf @method('DELETE')<button class="btn-link text-red-600" type="submit">Delete</button></form>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" class="px-4 py-3 text-gray-500">No data.</td></tr>
    @endforelse
  </tbody>
</table>
</div></div>
@endsection
