@extends('layouts.app')
@section('content')
<div class="p-4 max-w-2xl">
  <h1 class="text-xl font-semibold mb-4">New supplier</h1>
  <form class="card p-4 space-y-4" method="POST" action="{{ route('admin.suppliers.store') }}">
    @csrf
    @include('admin.suppliers.partials.form')
    <div class="flex gap-2">
      <button class="btn btn-primary" type="submit">Create</button>
      <a class="btn" href="{{ route('admin.suppliers.index') }}">Cancel</a>
    </div>
  </form>
</div>
@endsection
