@extends('layouts.app')
@section('content')
<h1 class="page-title mb-4">Add Deployment</h1>
<form action="{{ route('admin.deployments.store') }}" method="POST" class="max-w-2xl grid gap-4">
  @csrf
  <div class="form-group"><label class="form-label">Truck</label>
    <select name="truck_id" class="form-input">
      @foreach($trucks as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ $t->license_plate }})</option>@endforeach
    </select>
  </div>
  <div class="form-group"><label class="form-label">Location</label>
    <select name="location_id" class="form-input">
      @foreach($locations as $l)<option value="{{ $l->id }}">{{ $l->label }}</option>@endforeach
    </select>
  </div>
  <div class="grid grid-cols-2 gap-3">
    <div><label class="form-label">Starts at</label><input type="datetime-local" name="starts_at" class="form-input" value="{{ old('starts_at') }}"></div>
    <div><label class="form-label">Ends at</label><input type="datetime-local" name="ends_at" class="form-input" value="{{ old('ends_at') }}"></div>
  </div>
  <div class="flex items-center gap-3"><button class="btn-primary" type="submit">Save</button><a class="btn-link" href="{{ route('admin.deployments.index') }}">Cancel</a></div>
</form>
@endsection
