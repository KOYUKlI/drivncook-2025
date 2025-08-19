@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">{{ __('Payments') }}</h1>
<table class="min-w-full bg-white border">
  <thead class="bg-gray-50 text-xs uppercase">
    <tr>
      <th class="px-3 py-2 text-left">#</th>
  <th class="px-3 py-2 text-left">{{ __('Order') }}</th>
  <th class="px-3 py-2 text-left">{{ __('Amount') }}</th>
  <th class="px-3 py-2 text-left">{{ __('Method') }}</th>
  <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
      <th class="px-3 py-2"></th>
    </tr>
  </thead>
  <tbody class="divide-y">
  @foreach($payments as $p)
    <tr>
      <td class="px-3 py-2">{{ $p->id }}</td>
      <td class="px-3 py-2">#{{ $p->order->id }}</td>
  <td class="px-3 py-2">€ {{ number_format($p->amount,2) }}</td>
      <td class="px-3 py-2">{{ $p->method }}</td>
      <td class="px-3 py-2">{{ $p->status }}</td>
      <td class="px-3 py-2 text-right">
  <a href="{{ route('admin.payments.show',$p) }}" class="text-sm text-indigo-600 hover:underline">{{ __('View') }}</a>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
<div class="mt-4">{{ $payments->links() }}</div>
@endsection
