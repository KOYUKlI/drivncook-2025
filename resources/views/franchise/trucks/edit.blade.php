@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Éditer le camion</h1>

<form action="{{ route('franchise.trucks.update', $truck) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')
    <div class="form-group">
    <label class="form-label">Nom du camion</label>
        <input type="text" name="name" value="{{ old('name', $truck->name) }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
    <label class="form-label">Immatriculation</label>
        <input type="text" name="license_plate" value="{{ old('license_plate', $truck->license_plate) }}" class="form-input">
        @error('license_plate') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Mettre à jour</button>
    <a href="{{ route('franchise.trucks.index') }}" class="btn-link ml-3">Annuler</a>
</form>
@endsection