@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Warehouse Details</h1>

<div class="bg-white p-6 rounded shadow max-w-md">
    <p><strong>Name:</strong> {{ $warehouse->name }}</p>
    <p><strong>Location:</strong> {{ $warehouse->location }}</p>
    <p><strong>Franchise:</strong> {{ $warehouse->franchise->name }}</p>
    <!-- On pourrait lister les commandes de stock liées à cet entrepôt -->
</div>

<a href="{{ route('admin.warehouses.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to Warehouses list</a>
@endsection