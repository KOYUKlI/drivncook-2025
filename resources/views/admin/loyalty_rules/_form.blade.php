<div class="grid md:grid-cols-3 gap-4">
  <div>
  <label class="block text-sm font-medium">{{ __('Points / €') }}</label>
    <input type="number" step="0.0001" name="points_per_euro" value="{{ old('points_per_euro',$rule->points_per_euro) }}" class="w-full border rounded p-2">
    @error('points_per_euro') <p class="text-red-600 text-xs">{{ $message }}</p>@enderror
  </div>
  <div>
  <label class="block text-sm font-medium">{{ __('Redemption rate (1 pt => €)') }}</label>
    <input type="number" step="0.0001" name="redeem_rate" value="{{ old('redeem_rate',$rule->redeem_rate) }}" class="w-full border rounded p-2">
    @error('redeem_rate') <p class="text-red-600 text-xs">{{ $message }}</p>@enderror
  </div>
  <div>
  <label class="block text-sm font-medium">{{ __('Expiration (months)') }}</label>
    <input type="number" name="expires_after_months" value="{{ old('expires_after_months',$rule->expires_after_months) }}" class="w-full border rounded p-2">
    @error('expires_after_months') <p class="text-red-600 text-xs">{{ $message }}</p>@enderror
  </div>
</div>
<div class="mt-4 flex items-center gap-3">
  <label class="inline-flex items-center gap-2">
    <input type="checkbox" name="active" value="1" {{ old('active',$rule->active)?'checked':'' }}>
    <span>{{ __('Active') }}</span>
  </label>
  <button class="bg-indigo-600 text-white px-4 py-2 rounded">{{ __('Save') }}</button>
  <a href="{{ route('admin.loyalty-rules.index') }}" class="text-gray-600">{{ __('Cancel') }}</a>
</div>
