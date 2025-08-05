@extends('layouts.app')

@section('content')
<h1>{{ $franchise->name }} – Dashboard</h1>
<p>Welcome, {{ Auth::user()->name }}. Here is an overview of your franchise:</p>
<ul>
    <li>Number of Trucks: <strong>{{ $truckCount }}</strong></li>
    <li>Total Sales: <strong>${{ number_format($totalSales, 2) }}</strong></li>
    <!-- You could list other info like top performing truck, etc. -->
</ul>
@endsection
