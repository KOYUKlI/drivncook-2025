@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.warehouses') }}"/>

<div class="mb-4">
    <a href="{{ route('bo.warehouses.create') }}" class="btn btn-primary">{{ __('ui.create') }}</a>
  </div>

  <div class="bg-white border rounded">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.name') }}</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.city') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.actions') }}</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($warehouses as $w)
        <tr>
          <td class="px-6 py-4">{{ $w->name }}</td>
          <td class="px-6 py-4">{{ $w->city }}</td>
          <td class="px-6 py-4 text-right">
            <a class="text-indigo-600" href="{{ route('bo.warehouses.edit', $w) }}">{{ __('ui.edit') }}</a>
            <form action="{{ route('bo.warehouses.destroy', $w) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-3" onclick="return confirm('Supprimer ?')">{{ __('ui.delete') }}</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
