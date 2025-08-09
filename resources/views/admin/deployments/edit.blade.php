@extends('layouts.app')
@section('content')
<h1 class="page-title mb-4">Edit Deployment</h1>
<form action="{{ route('admin.deployments.update', ['deployment' => $deployment]) }}" method="POST" class="max-w-2xl grid gap-4">
  @csrf @method('PUT')
  <div class="form-group"><label class="form-label">Truck</label>
    <select name="truck_id" class="form-input">
      @foreach($trucks as $t)<option value="{{ $t->id }}" @selected(old('truck_id', $deployment->truck_id) == $t->id)>{{ $t->name }} ({{ $t->license_plate }})</option>@endforeach
    </select>
  </div>
  <div class="form-group"><label class="form-label">Location</label>
    <select name="location_id" class="form-input">
      @foreach($locations as $l)<option value="{{ $l->id }}" @selected(old('location_id', $deployment->location_id) == $l->id)>{{ $l->label }}</option>@endforeach
    </select>
  </div>
  <div class="grid grid-cols-2 gap-3">
    <div><label class="form-label">Starts at</label><input type="datetime-local" name="starts_at" class="form-input" value="{{ old('starts_at', $deployment->starts_at?->format('Y-m-d\TH:i')) }}"></div>
    <div><label class="form-label">Ends at</label><input type="datetime-local" name="ends_at" class="form-input" value="{{ old('ends_at', optional($deployment->ends_at)->format('Y-m-d\TH:i')) }}"></div>
  </div>
  <div class="flex items-center gap-3"><button class="btn-primary" type="submit">Save</button><a class="btn-link" href="{{ route('admin.deployments.index') }}">Cancel</a></div>
</form>
@endsection
