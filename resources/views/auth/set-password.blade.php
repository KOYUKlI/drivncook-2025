@extends('layouts.guest')
@section('content')
<div class="max-w-md mx-auto py-12">
  <div class="card p-6">
    <h1 class="text-xl font-semibold mb-4">Définir votre mot de passe</h1>
    @if ($errors->any())
      <div class="mb-4 p-3 rounded bg-red-50 text-red-700">Corrigez les erreurs.</div>
    @endif
    <form method="POST" action="{{ url()->current() }}?id={{ $user->id }}&expires={{ request('expires') }}&signature={{ urlencode(request('signature')) }}">
      @csrf
      <div class="mb-3">
        <label class="block text-sm font-medium">Nouveau mot de passe</label>
        <input type="password" name="password" class="input w-full" required>
        @error('password')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="block text-sm font-medium">Confirmez le mot de passe</label>
        <input type="password" name="password_confirmation" class="input w-full" required>
      </div>
      <button class="btn btn-primary w-full">Valider</button>
    </form>
  </div>
</div>
@endsection
