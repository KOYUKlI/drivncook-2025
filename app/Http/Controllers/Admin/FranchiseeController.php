<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FranchiseeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $franchises = Franchise::all();
        // Backfill ULIDs if missing to ensure route generation works
        $franchises->filter(fn($f) => empty($f->ulid))
            ->each(function(Franchise $f) {
                DB::table('franchises')->where('id', $f->id)->update(['ulid' => (string) Str::ulid()]);
                $f->refresh();
            });
        return view('admin.franchisees.index', compact('franchises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.franchisees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:franchises,name'
        ]);
        Franchise::create($request->only('name'));
        return redirect()->route('admin.franchisees.index')
                         ->with('success', 'Franchise created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Franchise $franchise)
    {
        if (empty($franchise->ulid)) {
            DB::table('franchises')->where('id', $franchise->id)->update(['ulid' => (string) Str::ulid()]);
            $franchise->refresh();
        }
        // Charger les relations pour afficher les détails (camions, entrepôts, etc.)
        $franchise->load(['trucks', 'warehouses', 'users']);
        return view('admin.franchisees.show', compact('franchise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Franchise $franchise)
    {
        if (empty($franchise->ulid)) {
            DB::table('franchises')->where('id', $franchise->id)->update(['ulid' => (string) Str::ulid()]);
            $franchise->refresh();
        }
        return view('admin.franchisees.edit', compact('franchise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Franchise $franchise)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:franchises,name,' . $franchise->id
        ]);
        $franchise->update($request->only('name'));
        return redirect()->route('admin.franchisees.index')
                         ->with('success', 'Franchise updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Franchise $franchise)
    {
        $franchise->delete();
        return redirect()->route('admin.franchisees.index')
                         ->with('success', 'Franchise deleted successfully.');
    }
}
