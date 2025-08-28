@extends('layouts.guest')

@section('content')
<div class="text-center py-20">
  <h1 class="text-4xl font-bold mb-2">500</h1>
  <p class="text-gray-600">{{ __('Server Error') }}</p>
  <p class="text-gray-500 mt-2">{{ __('Please try again later.') }}</p>
</div>
@endsection
