<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FranchiseeController extends Controller
{
    /**
     * Display a listing of franchisees.
     */
    public function index()
    {
        // Mock data
        $franchisees = [
            ['id' => 1, 'name' => 'Franchise Paris Nord', 'email' => 'paris.nord@drivncook.fr', 'status' => 'active', 'revenue' => 45000],
            ['id' => 2, 'name' => 'Franchise Lyon Centre', 'email' => 'lyon.centre@drivncook.fr', 'status' => 'active', 'revenue' => 38000],
            ['id' => 3, 'name' => 'Franchise Marseille Sud', 'email' => 'marseille.sud@drivncook.fr', 'status' => 'pending', 'revenue' => 0],
        ];

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
        // Validation and storage logic here
        return redirect()->route('bo.franchisees.index')->with('success', 'Franchisé créé avec succès');
    }

    /**
     * Display the specified franchisee.
     */
    public function show(string $id)
    {
        // Mock data
        $franchisee = ['id' => $id, 'name' => 'Franchise Paris Nord', 'email' => 'paris.nord@drivncook.fr', 'status' => 'active'];

        return view('bo.franchisees.show', compact('franchisee'));
    }

    /**
     * Show the form for editing the specified franchisee.
     */
    public function edit(string $id)
    {
        // Mock data
        $franchisee = ['id' => $id, 'name' => 'Franchise Paris Nord', 'email' => 'paris.nord@drivncook.fr', 'status' => 'active'];

        return view('bo.franchisees.edit', compact('franchisee'));
    }

    /**
     * Update the specified franchisee.
     */
    public function update(Request $request, string $id)
    {
        // Validation and update logic here
        return redirect()->route('bo.franchisees.index')->with('success', 'Franchisé modifié avec succès');
    }

    /**
     * Remove the specified franchisee.
     */
    public function destroy(string $id)
    {
        // Deletion logic here
        return redirect()->route('bo.franchisees.index')->with('success', 'Franchisé supprimé avec succès');
    }
}
