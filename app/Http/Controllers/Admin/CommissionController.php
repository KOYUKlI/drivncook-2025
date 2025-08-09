<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->integer('year');
        $month = $request->integer('month');
        $query = Commission::query()->with('franchisee');
        if ($year) { $query->where('period_year', $year); }
        if ($month) { $query->where('period_month', $month); }
        $commissions = $query->orderByDesc('period_year')->orderByDesc('period_month')->paginate(20)->withQueryString();
        return view('admin.commissions.index', compact('commissions', 'year', 'month'));
    }

    public function show(Commission $commission): View
    {
        return view('admin.commissions.show', compact('commission'));
    }

    public function update(Request $request, Commission $commission): RedirectResponse
    {
        $action = $request->string('action');
        if ($action === 'mark_paid') {
            $commission->update(['status' => 'paid', 'paid_at' => now()]);
        } elseif ($action === 'cancel') {
            $commission->update(['status' => 'canceled']);
        } elseif ($action === 'pending') {
            $commission->update(['status' => 'pending', 'paid_at' => null]);
        }
        return redirect()->back();
    }
}
