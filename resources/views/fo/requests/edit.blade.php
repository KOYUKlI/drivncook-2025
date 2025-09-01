@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
<x-ui.page-header :title="__('ui.fo.orders_request.edit.title', ['ref' => $order->reference])" />
<div class="max-w-4xl mx-auto">
    <x-ui.card>
        @if ($errors->any())
            <div class="mb-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('fo.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                @foreach($order->lines as $idx => $line)
                    <div class="grid grid-cols-12 gap-2">
                        <div class="col-span-8">
                            <select name="lines[{{ $idx }}][stock_item_id]" class="w-full border rounded px-2 py-1">
                                @foreach($items as $it)
                                    <option value="{{ $it->id }}" @selected($it->id===$line->stock_item_id)>{{ $it->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <input type="number" name="lines[{{ $idx }}][qty]" value="{{ $line->qty }}" min="1" class="w-full border rounded px-2 py-1" />
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 flex gap-2">
                <x-ui.button type="submit">{{ __('ui.common.save') }}</x-ui.button>
                <a href="{{ route('fo.orders.show', $order) }}" class="text-sm text-gray-600">{{ __('ui.common.cancel') }}</a>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
