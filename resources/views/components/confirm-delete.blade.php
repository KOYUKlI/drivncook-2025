@props([
    'name',
    'action',
    'title' => 'Confirm deletion',
    'message' => 'This action cannot be undone.',
    'method' => 'DELETE',
    'confirmLabel' => 'Delete',
    'cancelLabel' => 'Cancel',
])

<x-modal :name="$name" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">{{ $title }}</h2>
        <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn-secondary"
                x-on:click="$dispatch('close-modal', '{{ $name }}')">{{ $cancelLabel }}</button>
            <form method="POST" action="{{ $action }}">
                @csrf
                @method($method)
                <button class="btn-danger">{{ $confirmLabel }}</button>
            </form>
        </div>
    </div>
  </x-modal>
