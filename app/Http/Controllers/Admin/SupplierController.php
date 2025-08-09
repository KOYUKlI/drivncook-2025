<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::orderBy('name')->paginate(15);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:190',
            'siret' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:40',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Supplier::create($data);
        return redirect()->route('admin.suppliers.index');
    }

    public function show(Supplier $supplier): View
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:190',
            'siret' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:40',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $supplier->update($data);
        return redirect()->route('admin.suppliers.index');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index');
    }
}
