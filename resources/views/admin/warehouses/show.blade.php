@extends('layouts.app')

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Warehouse Details</h1>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="btn-primary">Edit</a>
        <a href="{{ route('admin.warehouses.index') }}" class="btn-secondary">Back</a>
    </div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">Info</div>
        <div class="card-body">
            <dl class="divide-y divide-gray-100">
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-600">Name</dt>
                    <dd class="font-medium text-gray-900">{{ $warehouse->name }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-600">Location</dt>
                    <dd class="font-medium text-gray-900">{{ $warehouse->location }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-600">Capacity</dt>
                    <dd class="font-medium text-gray-900">{{ $warehouse->capacity }}</dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Meta</div>
        <div class="card-body">
            <dl class="divide-y divide-gray-100">
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-600">Created</dt>
                    <dd class="font-medium text-gray-900">{{ optional($warehouse->created_at)->format('Y-m-d H:i') }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-600">Updated</dt>
                    <dd class="font-medium text-gray-900">{{ optional($warehouse->updated_at)->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection