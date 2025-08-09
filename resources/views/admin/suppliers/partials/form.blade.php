<div class="grid grid-cols-1 gap-4">
  <label class="form-control">
    <span class="form-label">Name</span>
    <input name="name" type="text" class="input" value="{{ old('name', $supplier->name ?? '') }}" required />
    @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </label>
  <label class="form-control">
    <span class="form-label">SIRET</span>
    <input name="siret" type="text" class="input" value="{{ old('siret', $supplier->siret ?? '') }}" />
    @error('siret')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </label>
  <label class="form-control">
    <span class="form-label">Contact email</span>
    <input name="contact_email" type="email" class="input" value="{{ old('contact_email', $supplier->contact_email ?? '') }}" />
    @error('contact_email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </label>
  <label class="form-control">
    <span class="form-label">Phone</span>
    <input name="phone" type="text" class="input" value="{{ old('phone', $supplier->phone ?? '') }}" />
    @error('phone')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </label>
  <label class="inline-flex items-center gap-2">
    <input name="is_active" type="checkbox" value="1" @checked(old('is_active', ($supplier->is_active ?? true))) />
    <span>Active</span>
  </label>
</div>
