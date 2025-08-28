@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.monthly_sales_reports') }}"/>

<form method="POST" action="{{ route('bo.reports.monthly.generate') }}" class="bg-white p-6 border rounded inline-block">
  @csrf
  <x-primary-button>{{ __('ui.generate_report') }}</x-primary-button>
 </form>
@endsection
