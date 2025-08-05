@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>
<p>Welcome, {{ Auth::user()->name }}. Here are some stats:</p>
<ul>
    <li>Total Franchises: <strong>{{ $franchiseCount ?? '0' }}</strong></li>
    <li>Total Trucks: <strong>{{ $truckCount ?? '0' }}</strong></li>
    <li>Total Sales: <strong>${{ $totalSales ?? '0.00' }}</strong></li>
</ul>
@endsection
