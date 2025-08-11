@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Éditer le franchisé</h1>

<form action="{{ route('admin.franchisees.update', ['franchisee' => $franchise->getRouteKey()]) }}" method="POST" class="max-w-md">
    @csrf @method('PUT')
    <div class="form-group">
    <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $franchise->name) }}">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
    <button type="submit" class="btn-primary">Enregistrer</button>
    <a href="{{ route('admin.franchisees.index') }}" class="btn-link">Annuler</a>
    </div>
</form>
@endsection
