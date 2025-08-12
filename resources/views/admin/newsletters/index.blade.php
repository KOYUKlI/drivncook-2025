@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Newsletters</h1>
<a href="{{ route('admin.newsletters.create') }}" class="btn btn-primary">New</a>
<table class="table-auto w-full mt-4">
<thead><tr><th>ID</th><th>Subject</th><th>Scheduled</th><th>Sent</th><th>Recipients</th><th></th></tr></thead>
<tbody>
@foreach($newsletters as $n)
<tr>
<td>{{ $n->id }}</td>
<td><a href="{{ route('admin.newsletters.show',$n) }}" class="text-blue-600">{{ $n->subject }}</a></td>
<td>{{ optional($n->scheduled_at)->format('Y-m-d H:i') }}</td>
<td>{{ optional($n->sent_at)->format('Y-m-d H:i') }}</td>
<td>{{ $n->recipients()->count() }}</td>
<td>
@if(!$n->sent_at)
<form method="POST" action="{{ route('admin.newsletters.send',$n) }}">@csrf<button class="text-sm text-green-600">Send</button></form>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
@endsection
