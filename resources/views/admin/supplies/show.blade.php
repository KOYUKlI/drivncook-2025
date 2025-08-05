@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Supply Details</h1>

<div class="bg-white p-6 rounded shadow max-w-md">
    <p><strong>Name:</strong> {{ $supply->name }}</p>
    <p><strong>Unit:</strong> {{ $supply->unit }}</p>
    <p><strong>Cost:</strong> ${{ number_format($supply->cost, 2) }}</p>
    <!-- On pourrait lister combien de fois cet ingrédient a été commandé, etc. -->
</div>

<a href="{{ route('admin.supplies.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to Supplies list</a>
@endsection