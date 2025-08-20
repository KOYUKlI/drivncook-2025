@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Franchise applications</h1>
    <div class="bg-white border rounded-lg">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2">Ville</th>
                    <th class="px-4 py-2">Budget</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $app->full_name }}</td>
                        <td class="px-4 py-2">{{ $app->email }}</td>
                        <td class="px-4 py-2 text-center">{{ $app->city ?? '—' }}</td>
                        <td class="px-4 py-2 text-center">{{ $app->budget ? number_format($app->budget,0,',',' ') . ' €' : '—' }}</td>
                        <td class="px-4 py-2 text-center">{{ ucfirst($app->status) }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('admin.franchise-applications.show', $app->id) }}" class="text-amber-700 hover:underline">Ouvrir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $applications->links() }}</div>
</div>
@endsection
