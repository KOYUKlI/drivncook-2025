<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreSupplyRequest;
use App\Http\Requests\Admin\UpdateSupplyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $supplies = Supply::all();  // Récupère tous les produits
        return view('admin.supplies.index', compact('supplies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.supplies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplyRequest $request): RedirectResponse
    {
        // Validation des champs (modèle: name, unit, cost)
    $validatedData = $request->validated();

        // Création du produit en base
        Supply::create($validatedData);

        // Redirection vers la liste avec un message de succès (optionnel)
        // session()->flash('success', 'Produit créé avec succès');
        return redirect()->route('admin.supplies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supply $supply): View
    {
        return view('admin.supplies.show', compact('supply'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supply $supply): View
    {
        return view('admin.supplies.edit', compact('supply'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplyRequest $request, Supply $supply): RedirectResponse
    {
        // Validation des champs (modèle: name, unit, cost)
    $validatedData = $request->validated();

        // Mise à jour du produit
        $supply->update($validatedData);

        // Redirection vers la liste avec un message de succès (optionnel)
        // session()->flash('success', 'Produit mis à jour avec succès');
        return redirect()->route('admin.supplies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supply $supply): RedirectResponse
    {
        $supply->delete();
        // session()->flash('success', 'Produit supprimé avec succès');
        return redirect()->route('admin.supplies.index');
    }
}
