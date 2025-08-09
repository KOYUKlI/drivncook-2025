@extends('layouts.app')

@section('content')
<div class="p-4">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Suppliers</h1>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">New supplier</a>
  </div>
  <div class="card overflow-x-auto">
    <table class="data-table w-full">
      <thead>
        <tr>
          <th>Name</th>
          <th>SIRET</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Status</th>
          <th class="w-40">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suppliers as $supplier)
        <tr>
          <td><a class="text-amber-700 hover:underline" href="{{ route('admin.suppliers.show', $supplier) }}">{{ $supplier->name }}</a></td>
          <td>{{ $supplier->siret }}</td>
          <td>{{ $supplier->contact_email }}</td>
          <td>{{ $supplier->phone }}</td>
          <td>
            @if($supplier->is_active)
              <span class="badge badge-success">active</span>
            @else
              <span class="badge">inactive</span>
            @endif
          </td>
          <td class="space-x-2">
            <a class="btn btn-secondary btn-sm" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
      <button class="btn btn-danger btn-sm" type="button" x-data x-on:click="$dispatch('open-modal', 'delete-supplier-{{ $supplier->id }}')">Delete</button>
      <x-confirm-delete :name="'delete-supplier-' . $supplier->id"
        :action="route('admin.suppliers.destroy', $supplier)"
        title="Delete supplier"
        :message="'Are you sure you want to delete ' . $supplier->name . '?'" />
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $suppliers->links() }}</div>
</div>
@endsection
