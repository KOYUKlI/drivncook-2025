@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.stock_items') }}"/>

<div class="mb-4">
    <a href="{{ route('bo.stock-items.create') }}" class="btn btn-primary">{{ __('ui.create') }}</a>
  </div>

  <div class="bg-white border rounded">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.name') }}</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.unit') }}</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.price') }}</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Central</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.actions') }}</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($items as $i)
        <tr>
          <td class="px-6 py-4">{{ $i->sku }}</td>
          <td class="px-6 py-4">{{ $i->name }}</td>
          <td class="px-6 py-4">{{ $i->unit }}</td>
          <td class="px-6 py-4">{{ number_format($i->price_cents/100, 2, ',', ' ') }}€</td>
          <td class="px-6 py-4">{{ $i->is_central ? '✓' : '—' }}</td>
          <td class="px-6 py-4 text-right">
            <a class="text-indigo-600" href="{{ route('bo.stock-items.edit', $i) }}">{{ __('ui.edit') }}</a>
            <form action="{{ route('bo.stock-items.destroy', $i) }}" method="POST" class="inline">
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
