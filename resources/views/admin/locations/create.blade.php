@extends('layouts.app')
@section('content')
<h1 class="page-title mb-4">Add Location</h1>
<form action="{{ route('admin.locations.store') }}" method="POST" class="max-w-2xl grid gap-4">
  @csrf
  <div class="form-group"><label class="form-label">Label</label><input name="label" class="form-input" value="{{ old('label') }}">@error('label')<p class="form-error">{{ $message }}</p>@enderror</div>
  <div class="form-group"><label class="form-label">Address</label><input name="address" class="form-input" value="{{ old('address') }}">@error('address')<p class="form-error">{{ $message }}</p>@enderror</div>
  <div class="grid grid-cols-3 gap-3">
    <div><label class="form-label">City</label><input name="city" class="form-input" value="{{ old('city') }}"></div>
    <div><label class="form-label">Postal code</label><input name="postal_code" class="form-input" value="{{ old('postal_code') }}"></div>
    <div class="grid grid-cols-2 gap-2">
      <div><label class="form-label">Lat</label><input name="lat" class="form-input" value="{{ old('lat') }}"></div>
      <div><label class="form-label">Lng</label><input name="lng" class="form-input" value="{{ old('lng') }}"></div>
    </div>
  </div>
  <div class="flex items-center gap-3">
    <button class="btn-primary" type="submit">Save</button>
    <a class="btn-link" href="{{ route('admin.locations.index') }}">Cancel</a>
  </div>
</form>
@endsection
