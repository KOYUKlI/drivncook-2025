@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Truck Requests</h1>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Franchise</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($requests as $r)
                <tr>
                    <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $r->franchise->name }}</td>
                    <td>{{ $r->requester->name }}</td>
                    <td><span class="badge">{{ ucfirst($r->status) }}</span></td>
                    <td class="text-right"><a class="btn-link" href="{{ route('admin.truckrequests.show',$r) }}">Review</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $requests->links() }}</div>
@endsection
