<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FranchiseeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Liste paginée avec compteurs pour éviter le N+1
        $franchises = Franchise::query()
            ->withCount(['trucks', 'warehouses'])
            ->orderBy('name')
            ->paginate(20);

        // Backfill ULIDs si manquants sans perdre les withCount (pas de refresh)
        $franchises->getCollection()
            ->filter(fn($f) => empty($f->ulid))
            ->each(function(Franchise $f) {
                $new = (string) Str::ulid();
                DB::table('franchises')->where('id', $f->id)->update(['ulid' => $new]);
                $f->ulid = $new;
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

    /**
     * Attach an existing user to the franchise by email and set role to 'franchise'.
     */
    public function attachUser(Request $request, Franchise $franchise)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->with('error', "No user exists with that email address.");
        }
        if ($user->role === 'admin') {
            return back()->with('error', "You can't attach an administrator.");
        }
        $user->franchise_id = $franchise->id;
        $user->role = 'franchise';
        $user->save();
    return back()->with('success', 'User attached to the franchisee.');
    }

    /**
     * Detach a user from the franchise.
     */
    public function detachUser(Franchise $franchise, User $user)
    {
        if ($user->franchise_id !== $franchise->id) {
            return back()->with('error', "This user is not attached to this franchisee.");
        }
        // On détache sans changer son rôle (au besoin, on pourra le repasser en 'user')
        $user->franchise_id = null;
        $user->save();
    return back()->with('success', 'User detached from the franchisee.');
    }
}
