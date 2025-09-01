@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
<x-ui.page-header :title="__('ui.fo.orders_request.create.title')" />
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
        <form method="POST" action="{{ route('fo.orders.store') }}">
            @csrf
            <div class="mb-4 text-sm text-gray-600">{{ __('ui.fo.orders_request.create.ref_preview') }} {{ \App\Models\PurchaseOrder::nextFpoReference() }}</div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">{{ __('ui.labels.items') }}</label>
                    <div id="lines" class="space-y-2">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-8">
                                <select name="lines[0][stock_item_id]" class="w-full border rounded px-2 py-1">
                                    @foreach($items as $it)
                                        <option value="{{ $it->id }}">{{ $it->name }} @if($it->is_central)• {{ __('ui.stock_items.type.central') }}@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4">
                                <input type="number" name="lines[0][qty]" value="1" min="1" class="w-full border rounded px-2 py-1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <x-ui.button type="submit">{{ __('ui.common.save') }}</x-ui.button>
                <a href="{{ route('fo.orders.index') }}" class="text-sm text-gray-600">{{ __('ui.common.cancel') }}</a>
            </div>
        </form>
    </x-ui.card>
    <p class="mt-2 text-xs text-amber-600">{{ __('ui.fo.orders_request.ratio_warning_hint') }}</p>
    <div class="mt-4"><a class="text-sm text-orange-600 hover:underline" href="{{ route('fo.orders.index') }}">{{ __('ui.fo.orders_request.back_to_list') }}</a></div>
</div>
@endsection
