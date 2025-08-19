@csrf
<div class="space-y-4">
  <div>
  <label class="block text-sm font-medium">{{ __('Subject') }}</label>
    <input name="subject" type="text" value="{{ old('subject', $newsletter->subject ?? '') }}" class="w-full border rounded p-2">
    @error('subject') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  <div>
  <label class="block text-sm font-medium">{{ __('Scheduled at') }}</label>
    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($newsletter->scheduled_at)->format('Y-m-d\\TH:i')) }}" class="w-full border rounded p-2">
    @error('scheduled_at') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  <div>
  <label class="block text-sm font-medium">{{ __('Content') }}</label>
    <textarea name="body" rows="8" class="w-full border rounded p-2">{{ old('body', $newsletter->body ?? '') }}</textarea>
    @error('body') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  <div class="flex items-center gap-4">
  <button class="bg-indigo-600 text-white px-4 py-2 rounded">{{ __('Save') }}</button>
  <a href="{{ route('admin.newsletters.index') }}" class="text-gray-600">{{ __('Cancel') }}</a>
  </div>
</div>
