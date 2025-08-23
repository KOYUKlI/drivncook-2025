@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Request an Additional Truck</h1>

<form action="{{ route('franchise.truckrequests.store') }}" method="POST" class="max-w-xl">
    @csrf
    <div class="form-group">
        <label class="form-label">Reason (optional)</label>
        <textarea name="reason" rows="4" class="form-textarea" placeholder="Explain why you need an additional truck...">{{ old('reason') }}</textarea>
        @error('reason') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Submit Request</button>
        <a href="{{ route('franchise.truckrequests.index') }}" class="btn-link">Cancel</a>
    </div>
</form>
@endsection
