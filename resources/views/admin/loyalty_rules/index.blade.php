@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Loyalty Rules</h1>
<a href="{{ route('admin.loyalty-rules.create') }}" class="btn btn-primary">New Rule</a>
<table class="table-auto w-full mt-4">
<thead><tr><th>ID</th><th>Points/€</th><th>Redeem Rate</th><th>Expires (months)</th><th>Active</th><th></th></tr></thead>
<tbody>
@foreach($rules as $r)
<tr>
<td>{{ $r->id }}</td>
<td>{{ $r->points_per_euro }}</td>
<td>{{ $r->redeem_rate }}</td>
<td>{{ $r->expires_after_months }}</td>
<td>{{ $r->active ? 'Yes':'No' }}</td>
<td><a href="{{ route('admin.loyalty-rules.edit',$r) }}" class="text-blue-600">Edit</a></td>
</tr>
@endforeach
</tbody>
</table>
@endsection
