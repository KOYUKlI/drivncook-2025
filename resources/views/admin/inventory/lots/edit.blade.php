<form method="POST" action="{{ route('admin.inventory.lots.update', [$inventory, $lot]) }}">
    @csrf
    @method('PUT')
    <input name="lot_code" placeholder="Lot code" value="{{ old('lot_code', $lot->lot_code) }}" />
    <input name="qty" type="number" step="0.001" placeholder="Quantity" value="{{ old('qty', $lot->qty) }}" />
    <input name="expires_at" type="date" value="{{ old('expires_at', optional($lot->expires_at)->format('Y-m-d')) }}" />
    <button type="submit">Update Lot</button>
</form>
