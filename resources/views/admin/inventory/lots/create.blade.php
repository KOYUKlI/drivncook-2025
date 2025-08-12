<form method="POST" action="{{ route('admin.inventory.lots.store', $inventory) }}">
    @csrf
    <input name="lot_code" placeholder="Lot code" value="{{ old('lot_code') }}" />
    <input name="qty" type="number" step="0.001" placeholder="Quantity" value="{{ old('qty') }}" />
    <input name="expires_at" type="date" value="{{ old('expires_at') }}" />
    <button type="submit">Create Lot</button>
</form>
