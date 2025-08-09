@php
    $success = session('success');
    $error = session('error');
    $status = session('status');
@endphp

@if($success)
    <div class="card mb-3 bg-green-50 ring-green-200">
        <div class="card-body text-green-800">{{ $success }}</div>
    </div>
@endif
@if($error)
    <div class="card mb-3 bg-red-50 ring-red-200">
        <div class="card-body text-red-800">{{ $error }}</div>
    </div>
@endif
@if($status)
    <div class="card mb-3 bg-blue-50 ring-blue-200">
        <div class="card-body text-blue-800">{{ $status }}</div>
    </div>
@endif
