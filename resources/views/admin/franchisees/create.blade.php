@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Ajouter un franchisé</h1>

<form action="{{ route('admin.franchisees.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="form-group">
    <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-input" value="{{ old('name') }}">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="flex items-center gap-3">
    <button type="submit" class="btn-primary">Créer</button>
    <a href="{{ route('admin.franchisees.index') }}" class="btn-link">Annuler</a>
    </div>
</form>
@endsection
