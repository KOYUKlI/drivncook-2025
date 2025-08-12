<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyRuleController extends Controller
{
    public function index(): View
    {
        $rules = LoyaltyRule::orderByDesc('created_at')->get();
        return view('admin.loyalty_rules.index', compact('rules'));
    }

    public function create(): View
    {
        return view('admin.loyalty_rules.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'points_per_euro' => 'required|numeric|min:0',
            'redeem_rate' => 'required|numeric|min:0',
            'expires_after_months' => 'nullable|integer|min:1',
            'active' => 'boolean'
        ]);
        if (!empty($data['active'])) {
            LoyaltyRule::where('active', true)->update(['active'=>false]);
        }
        LoyaltyRule::create($data);
        return redirect()->route('admin.loyalty-rules.index')->with('success','Rule created.');
    }

    public function edit(LoyaltyRule $loyalty_rule): View
    {
        return view('admin.loyalty_rules.edit', ['rule'=>$loyalty_rule]);
    }

    public function update(Request $request, LoyaltyRule $loyalty_rule): RedirectResponse
    {
        $data = $request->validate([
            'points_per_euro' => 'required|numeric|min:0',
            'redeem_rate' => 'required|numeric|min:0',
            'expires_after_months' => 'nullable|integer|min:1',
            'active' => 'boolean'
        ]);
        if (!empty($data['active'])) {
            LoyaltyRule::where('id','<>',$loyalty_rule->id)->where('active', true)->update(['active'=>false]);
        }
        $loyalty_rule->update($data);
        return redirect()->route('admin.loyalty-rules.index')->with('success','Rule updated.');
    }

    public function destroy(LoyaltyRule $loyalty_rule): RedirectResponse
    {
        $loyalty_rule->delete();
        return redirect()->route('admin.loyalty-rules.index')->with('success','Rule deleted.');
    }
}
