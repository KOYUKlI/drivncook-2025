@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.edit_warehouse') }}"/>

<form method="POST" action="{{ route('bo.warehouses.update', $warehouse) }}" class="space-y-4 bg-white p-6 border rounded">
  @csrf
  @method('PUT')
  <div>
    <x-input-label for="name" :value="__('ui.name')" />
    <x-text-input id="name" name="name" value="{{ old('name', $warehouse->name) }}" required />
    <x-input-error :messages="$errors->get('name')" />
  </div>
  <div>
    <x-input-label for="city" :value="__('ui.city')" />
    <x-text-input id="city" name="city" value="{{ old('city', $warehouse->city) }}" />
    <x-input-error :messages="$errors->get('city')" />
  </div>
  <x-primary-button>{{ __('ui.save') }}</x-primary-button>
  <a href="{{ route('bo.warehouses.index') }}" class="btn">{{ __('ui.cancel') }}</a>
 </form>
@endsection
