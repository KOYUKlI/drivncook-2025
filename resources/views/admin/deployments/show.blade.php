@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Deployment</h1>
  <a class="btn-secondary" href="{{ route('admin.deployments.edit', ['deployment' => $deployment]) }}">Edit</a>
</div>
<div class="card"><div class="card-body">
  <dl class="grid grid-cols-2 gap-2">
    <div><dt class="text-gray-500">Truck</dt><dd>{{ $deployment->truck?->name }}</dd></div>
    <div><dt class="text-gray-500">Location</dt><dd>{{ $deployment->location?->label }}</dd></div>
    <div><dt class="text-gray-500">Starts at</dt><dd>{{ $deployment->starts_at }}</dd></div>
    <div><dt class="text-gray-500">Ends at</dt><dd>{{ $deployment->ends_at ?? '—' }}</dd></div>
  </dl>
</div></div>
@endsection
