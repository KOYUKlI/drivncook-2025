@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Créer un utilisateur — {{ $franchise->name }}</h1>
    <form method="POST" action="{{ route('admin.franchises.users.store', $franchise) }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input" required>
            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required>
            @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Rôle</label>
            <select name="role" class="input" required>
                <option value="franchise" @selected(old('role')==='franchise')>Franchise</option>
                <option value="employee" @selected(old('role')==='employee')>Employé</option>
            </select>
            @error('role')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="pt-2">
            <button type="submit" class="btn btn-primary">Créer et envoyer l’invitation</button>
        </div>
    </form>
</div>
@endsection
