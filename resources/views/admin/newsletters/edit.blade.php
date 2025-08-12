@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Edit Newsletter #{{ $newsletter->id }}</h1>
<form method="POST" action="{{ route('admin.newsletters.update',$newsletter) }}" class="space-y-4">
@csrf @method('PUT')
<label class="block">Subject <input name="subject" value="{{ old('subject',$newsletter->subject) }}" class="border p-2 w-full"/></label>
<label class="block">Scheduled at <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at',optional($newsletter->scheduled_at)->format('Y-m-d\TH:i')) }}" class="border p-2 w-full"/></label>
<label class="block">Body <textarea name="body" rows="8" class="border p-2 w-full">{{ old('body',$newsletter->body) }}</textarea></label>
<button class="btn btn-primary">Update</button>
</form>
@endsection
