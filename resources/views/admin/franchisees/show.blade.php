@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Franchise: {{ $franchise->name }}</h1>
    <a href="{{ route('admin.franchisees.edit', $franchise) }}" class="btn-secondary">Edit</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card">
        <div class="card-header"><h2 class="font-semibold">Summary</h2></div>
        <div class="card-body">
            <dl class="text-sm text-gray-700 space-y-2">
                <div class="flex justify-between"><dt>Trucks</dt><dd>{{ $franchise->trucks->count() }}</dd></div>
                <div class="flex justify-between"><dt>Warehouses</dt><dd>{{ $franchise->warehouses->count() }}</dd></div>
                <div class="flex justify-between"><dt>Users</dt><dd>{{ $franchise->users->count() }}</dd></div>
            </dl>
        </div>
    </div>

    <div class="card md:col-span-2">
        <div class="card-header"><h2 class="font-semibold">Trucks</h2></div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Plate</th></tr></thead>
                <tbody>
                    @forelse($franchise->trucks as $truck)
                        <tr><td>{{ $truck->name }}</td><td>{{ $truck->license_plate }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-3 text-gray-500">No trucks yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header"><h2 class="font-semibold">Warehouses</h2></div>
    <div class="card-body p-0">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Location</th></tr></thead>
            <tbody>
                @forelse($franchise->warehouses as $warehouse)
                    <tr><td>{{ $warehouse->name }}</td><td>{{ $warehouse->location }}</td></tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-3 text-gray-500">No warehouses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
