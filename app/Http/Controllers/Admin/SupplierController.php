<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreSupplierRequest;
use App\Http\Requests\Admin\UpdateSupplierRequest;
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

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $data = $request->validated();
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

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validated();
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
