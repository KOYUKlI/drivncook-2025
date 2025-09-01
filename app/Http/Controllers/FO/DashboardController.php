<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $franchisee = $user->franchisee;

        // Redirect if user doesn't have a franchisee association
        if (!$franchisee) {
            // Do NOT redirect to the generic /dashboard here to avoid a redirect loop
            // because /dashboard will route franchisees back to fo.dashboard.
            return redirect()->route('fo.account.edit')
                ->with('error', __('ui.fo.errors.no_franchisee_association'));
        }

        // Get monthly sales total
        $monthlySales = Sale::where('franchisee_id', $franchisee->id)
            ->whereYear('sale_date', Carbon::now()->year)
            ->whereMonth('sale_date', Carbon::now()->month)
            ->sum('total_cents');

        // Get 30 days sales total
        $thirtyDaysSales = Sale::where('franchisee_id', $franchisee->id)
            ->where('sale_date', '>=', Carbon::now()->subDays(30))
            ->sum('total_cents');

        // Get recent sales
        $recentSales = Sale::where('franchisee_id', $franchisee->id)
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();

        return view('fo.dashboard', compact('monthlySales', 'thirtyDaysSales', 'recentSales'));
    }
}
