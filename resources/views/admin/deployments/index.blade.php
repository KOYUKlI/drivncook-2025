@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Truck Deployments</h1>
  <a href="{{ route('admin.deployments.create') }}" class="btn-primary">Add</a>
</div>
<div class="card"><div class="card-body p-0">
<table class="data-table">
  <thead><tr><th>Truck</th><th>Location</th><th>Starts</th><th>Ends</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($deployments as $d)
      <tr>
        <td>{{ $d->truck?->name }}</td>
        <td>{{ $d->location?->label }}</td>
        <td>{{ $d->starts_at }}</td>
        <td>{{ $d->ends_at ?? '—' }}</td>
        <td class="text-center space-x-2">
          <a class="btn-link" href="{{ route('admin.deployments.show', $d) }}">View</a>
          <a class="btn-link" href="{{ route('admin.deployments.edit', $d) }}">Edit</a>
          <form action="{{ route('admin.deployments.destroy', $d) }}" method="POST" class="inline">@csrf @method('DELETE')<button class="btn-link text-red-600" type="submit">Delete</button></form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="px-4 py-3 text-gray-500">No data.</td></tr>
    @endforelse
  </tbody>
</table>
</div></div>
@endsection
