@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Truck Requests</h1>
    <a href="{{ route('franchise.truckrequests.create') }}" class="btn-primary">Request a Truck</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Admin Note</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $r)
                <tr>
                    <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
                    <td><span class="badge">{{ ucfirst($r->status) }}</span></td>
                    <td class="text-sm text-gray-700">{{ $r->reason }}</td>
                    <td class="text-sm text-gray-700">{{ $r->admin_note }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-500">No requests yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
