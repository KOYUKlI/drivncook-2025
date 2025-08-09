@extends('layouts.app')
@section('content')
<div class="p-4 max-w-2xl">
  <h1 class="text-xl font-semibold mb-4">Edit supplier</h1>
  <form class="card p-4 space-y-4" method="POST" action="{{ route('admin.suppliers.update', $supplier) }}">
    @csrf
    @method('PUT')
    @include('admin.suppliers.partials.form', ['supplier' => $supplier])
    <div class="flex gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn" href="{{ route('admin.suppliers.index') }}">Cancel</a>
    </div>
  </form>
</div>
@endsection
