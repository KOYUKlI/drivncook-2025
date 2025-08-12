@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Edit Loyalty Rule #{{ $rule->id }}</h1>
<form method="POST" action="{{ route('admin.loyalty-rules.update',$rule) }}" class="space-y-4">
@csrf @method('PUT')
<label>Points per € <input name="points_per_euro" value="{{ old('points_per_euro',$rule->points_per_euro) }}" class="border p-2"/></label>
<label>Redeem rate (€ per point) <input name="redeem_rate" value="{{ old('redeem_rate',$rule->redeem_rate) }}" class="border p-2"/></label>
<label>Expires after (months) <input name="expires_after_months" value="{{ old('expires_after_months',$rule->expires_after_months) }}" class="border p-2"/></label>
<label><input type="checkbox" name="active" value="1" {{ old('active',$rule->active)?'checked':'' }}/> Active</label>
<button class="btn btn-primary">Update</button>
</form>
@endsection
