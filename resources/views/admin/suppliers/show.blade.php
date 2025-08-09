@extends('layouts.app')
@section('content')
<div class="p-4 max-w-3xl">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Supplier: {{ $supplier->name }}</h1>
    <a class="btn btn-secondary" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
  </div>
  <div class="card p-4 space-y-2">
    <div><span class="font-medium">SIRET:</span> {{ $supplier->siret ?: '—' }}</div>
    <div><span class="font-medium">Email:</span> {{ $supplier->contact_email ?: '—' }}</div>
    <div><span class="font-medium">Phone:</span> {{ $supplier->phone ?: '—' }}</div>
    <div><span class="font-medium">Status:</span> {{ $supplier->is_active ? 'active' : 'inactive' }}</div>
  </div>
</div>
@endsection
