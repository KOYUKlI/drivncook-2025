@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Create Newsletter</h1>
<form method="POST" action="{{ route('admin.newsletters.store') }}" class="space-y-4">
@csrf
<label class="block">Subject <input name="subject" value="{{ old('subject') }}" class="border p-2 w-full"/></label>
<label class="block">Scheduled at <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="border p-2 w-full"/></label>
<label class="block">Body <textarea name="body" rows="8" class="border p-2 w-full">{{ old('body') }}</textarea></label>
<button class="btn btn-primary">Save</button>
</form>
@endsection
