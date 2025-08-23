@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">My Trucks</h1>
    <a href="{{ route('franchise.truckrequests.create') }}" class="btn-primary">Request a Truck</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>License Plate</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($trucks as $truck)
                <tr>
                    <td>{{ $truck->name }}</td>
                    <td>{{ $truck->license_plate }}</td>
                    <td class="text-center space-x-2">
                        <a href="{{ route('franchise.trucks.show', ['truck' => $truck]) }}" class="btn-link">View</a>
                        <a href="{{ route('franchise.trucks.edit', ['truck' => $truck]) }}" class="btn-link">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection