<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Franchisee;
use App\Models\ReportPdf;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FranchiseeController extends Controller
{
    /**
     * Display a listing of franchisees.
     */
    public function index()
    {
        $franchisees = Franchisee::query()
            ->latest()
            ->get();

        return view('bo.franchisees.index', compact('franchisees'));
    }

    /**
     * Show the form for creating a new franchisee.
     */
    public function create()
    {
        return view('bo.franchisees.create');
    }

    /**
     * Store a newly created franchisee.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'billing_address' => 'nullable|string|max:1000',
        ]);

        $franchisee = new Franchisee();
        $franchisee->id = (string) Str::ulid();
        $franchisee->fill($data);
        $franchisee->save();

        return redirect()->route('bo.franchisees.show', $franchisee->id)
            ->with('success', __('Franchisé créé avec succès'));
    }

    /**
     * Display the specified franchisee.
     */
    public function show(string $id)
    {
        $franchisee = Franchisee::with(['trucks', 'sales.lines'])->findOrFail($id);

        $from30 = now()->subDays(30);
        $from60 = now()->subDays(60);

        $sales30 = Sale::where('franchisee_id', $franchisee->id)->where('created_at', '>=', $from30)->get();
        $sales60 = Sale::where('franchisee_id', $franchisee->id)->where('created_at', '>=', $from60)->get();

        $stats = [
            'total_revenue_30d' => (int) $sales30->sum('total_cents'),
            'sales_count_30d' => (int) $sales30->count(),
            'avg_transaction' => $sales30->count() ? (int) floor($sales30->avg('total_cents')) : 0,
            'total_revenue_60d' => (int) $sales60->sum('total_cents'),
            'sales_count_60d' => (int) $sales60->count(),
            'trucks_assigned' => (int) $franchisee->trucks->count(),
        ];

        $reports = ReportPdf::where('franchisee_id', $franchisee->id)->latest('generated_at')->get();

        return view('bo.franchisees.show', compact('franchisee', 'stats', 'reports'));
    }

    /**
     * Show the form for editing the specified franchisee.
     */
    public function edit(string $id)
    {
    $franchisee = Franchisee::findOrFail($id);

    return view('bo.franchisees.edit', compact('franchisee'));
    }

    /**
     * Update the specified franchisee.
     */
    public function update(Request $request, string $id)
    {
        $franchisee = Franchisee::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'billing_address' => 'nullable|string|max:1000',
        ]);
        $franchisee->fill($data)->save();

        return redirect()->route('bo.franchisees.show', $franchisee->id)
            ->with('success', __('Franchisé modifié avec succès'));
    }

    /**
     * Remove the specified franchisee.
     */
    public function destroy(string $id)
    {
    $franchisee = Franchisee::findOrFail($id);
    $franchisee->delete();
    return redirect()->route('bo.franchisees.index')->with('success', __('Franchisé supprimé avec succès'));
    }
}
