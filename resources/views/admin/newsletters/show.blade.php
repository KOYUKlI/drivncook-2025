@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-2">Newsletter #{{ $newsletter->id }}</h1>
<p><strong>Subject:</strong> {{ $newsletter->subject }}</p>
<p><strong>Scheduled:</strong> {{ optional($newsletter->scheduled_at)->format('Y-m-d H:i') }}</p>
<p><strong>Sent:</strong> {{ optional($newsletter->sent_at)->format('Y-m-d H:i') ?? '—' }}</p>
<div class="prose border p-4 my-4">{!! nl2br(e($newsletter->body)) !!}</div>
@if(!$newsletter->sent_at)
<form method="POST" action="{{ route('admin.newsletters.send',$newsletter) }}" class="inline">@csrf <button class="btn btn-success">Send now</button></form>
<a href="{{ route('admin.newsletters.edit',$newsletter) }}" class="btn btn-secondary">Edit</a>
@endif
<a href="{{ route('admin.newsletters.index') }}" class="btn">Back</a>
@endsection
