@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Location: {{ $location->label }}</h1>
  <a class="btn-secondary" href="{{ route('admin.locations.edit', ['location' => $location]) }}">Edit</a>
</div>
<div class="card"><div class="card-body">
  <dl class="grid grid-cols-2 gap-2">
    <div><dt class="text-gray-500">Address</dt><dd>{{ $location->address }}</dd></div>
    <div><dt class="text-gray-500">City</dt><dd>{{ $location->city }}</dd></div>
    <div><dt class="text-gray-500">Postal code</dt><dd>{{ $location->postal_code }}</dd></div>
    <div><dt class="text-gray-500">Coordinates</dt><dd>{{ $location->lat }}, {{ $location->lng }}</dd></div>
  </dl>
</div></div>
@endsection
