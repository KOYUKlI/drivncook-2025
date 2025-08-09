@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Supply Details</h1>
  <a href="{{ route('admin.supplies.edit', $supply) }}" class="btn-secondary">Edit</a>
</div>

<div class="card max-w-xl">
    <div class="card-body">
        <dl class="divide-y divide-gray-100">
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ $supply->name }}</dd>
            </div>
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Unit</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ $supply->unit }}</dd>
            </div>
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Cost</dt>
                <dd class="col-span-2 text-sm text-gray-900">${{ number_format($supply->cost, 2) }}</dd>
            </div>
        </dl>
    </div>
</div>

<div class="mt-4">
  <a href="{{ route('admin.supplies.index') }}" class="btn-link">← Back to Supplies list</a>
</div>
@endsection