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
        $this->authorize('viewAny', Franchisee::class);
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
        $this->authorize('create', Franchisee::class);

        return view('bo.franchisees.create');
    }

    /**
     * Store a newly created franchisee.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Franchisee::class);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:franchisees,email',
            'phone' => 'nullable|string|max:30',
            'billing_address' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.email' => 'L\'email doit être valide.',
        ]);

        $franchisee = new Franchisee();
        $franchisee->id = (string) Str::ulid();
        $franchisee->name = $data['name'];
        $franchisee->email = $data['email'];
        $franchisee->phone = $data['phone'] ?? null;
        $franchisee->billing_address = $data['billing_address'] ?? null;
        $franchisee->save();

        return redirect()->route('bo.franchisees.show', $franchisee->id)
            ->with('success', 'Franchisé créé avec succès.');
    }

    /**
     * Display the specified franchisee.
     */
    public function show(string $id)
    {
        $franchisee = Franchisee::with(['trucks', 'sales.lines'])->findOrFail($id);
        $this->authorize('view', $franchisee);

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

        // For now, reports are not linked to franchisees directly
        $reports = collect([]);

        return view('bo.franchisees.show', compact('franchisee', 'stats', 'reports'));
    }

    /**
     * Show the form for editing the specified franchisee.
     */
    public function edit(string $id)
    {
        $franchisee = Franchisee::findOrFail($id);
        $this->authorize('update', $franchisee);

        return view('bo.franchisees.edit', compact('franchisee'));
    }

    /**
     * Update the specified franchisee.
     */
    public function update(Request $request, string $id)
    {
        $franchisee = Franchisee::findOrFail($id);
        $this->authorize('update', $franchisee);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:franchisees,email,'.$id,
            'phone' => 'nullable|string|max:30',
            'billing_address' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé par un autre franchisé.',
            'email.email' => 'L\'email doit être valide.',
        ]);
        $franchisee->fill($data)->save();

        return redirect()->route('bo.franchisees.show', $franchisee->id)
            ->with('success', 'Franchisé modifié avec succès.');
    }

    /**
     * Remove the specified franchisee.
     */
    public function destroy(string $id)
    {
        $franchisee = Franchisee::findOrFail($id);
        $this->authorize('delete', $franchisee);
        $franchisee->delete();

        return redirect()->route('bo.franchisees.index')->with('success', __('Franchisé supprimé avec succès'));
    }
}
