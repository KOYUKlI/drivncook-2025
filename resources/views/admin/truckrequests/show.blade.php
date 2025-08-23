@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Review Truck Request</h1>
  <a href="{{ route('admin.truckrequests.index') }}" class="btn-link">Back</a>
</div>

<div class="card max-w-3xl">
  <div class="card-body space-y-4">
    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="text-sm text-gray-500">Franchise</div>
        <div class="font-medium">{{ $truckrequest->franchise->name }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Requested By</div>
        <div class="font-medium">{{ $truckrequest->requester->name }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Status</div>
        <div class="font-medium">{{ ucfirst($truckrequest->status) }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Created</div>
        <div class="font-medium">{{ $truckrequest->created_at->format('Y-m-d H:i') }}</div>
      </div>
    </div>
    <div>
      <div class="text-sm text-gray-500">Reason</div>
      <div class="text-gray-800">{{ $truckrequest->reason ?: '—' }}</div>
    </div>
    @if($truckrequest->status !== 'pending')
      <div>
        <div class="text-sm text-gray-500">Admin Note</div>
        <div class="text-gray-800">{{ $truckrequest->admin_note ?: '—' }}</div>
      </div>
    @endif
  </div>
</div>

@if($truckrequest->status === 'pending')
<div class="mt-6 card max-w-3xl">
  <div class="card-header"><h2 class="text-lg font-semibold">Process Request</h2></div>
  <div class="card-body">
    <form action="{{ route('admin.truckrequests.update', $truckrequest) }}" method="POST" x-data="{ create:false }">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label class="form-label">Admin Note</label>
        <textarea name="admin_note" rows="3" class="form-textarea">{{ old('admin_note') }}</textarea>
      </div>
      <div class="form-group">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="create_truck" value="1" x-model="create" class="form-checkbox">
          <span>Create and assign a truck on approval</span>
        </label>
      </div>
      <div class="grid grid-cols-2 gap-4" x-show="create">
        <div class="form-group">
          <label class="form-label">Truck Name</label>
          <input type="text" name="truck_name" class="form-input" value="{{ old('truck_name') }}">
        </div>
        <div class="form-group">
          <label class="form-label">License Plate (optional)</label>
          <input type="text" name="license_plate" class="form-input" value="{{ old('license_plate') }}">
        </div>
      </div>
      <div class="flex items-center gap-3">
        <button name="action" value="approve" class="btn-primary">Approve</button>
        <button name="action" value="reject" class="btn-danger">Reject</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection
