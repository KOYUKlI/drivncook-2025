@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Dish</h1>
  <a href="{{ route('admin.dishes.index') }}" class="btn">Back</a>
</div>
<div class="card p-4 space-y-3 max-w-3xl">
  <div><span class="font-medium">Name:</span> {{ $dish->name }}</div>
  <div><span class="font-medium">Price:</span> {{ number_format($dish->price, 2) }} €</div>
  <div>
    <span class="font-medium">Ingredients:</span>
    <ul class="list-disc list-inside">
      @foreach($dish->ingredients as $ing)
        <li>{{ $ing->qty_per_dish }} {{ $ing->unit }} — {{ $ing->supply->name }}</li>
      @endforeach
    </ul>
  </div>
</div>
@endsection
