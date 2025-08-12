@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Create Loyalty Rule</h1>
<form method="POST" action="{{ route('admin.loyalty-rules.store') }}" class="space-y-4">
@csrf
<label>Points per € <input name="points_per_euro" value="{{ old('points_per_euro') }}" class="border p-2"/></label>
<label>Redeem rate (€ per point) <input name="redeem_rate" value="{{ old('redeem_rate') }}" class="border p-2"/></label>
<label>Expires after (months) <input name="expires_after_months" value="{{ old('expires_after_months') }}" class="border p-2"/></label>
<label><input type="checkbox" name="active" value="1" {{ old('active')?'checked':'' }}/> Active</label>
<button class="btn btn-primary">Save</button>
</form>
@endsection
