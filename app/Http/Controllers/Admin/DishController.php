<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DishController extends Controller
{
    public function index(): View
    {
        $dishes = Dish::orderBy('name')->paginate(20);
        return view('admin.dishes.index', compact('dishes'));
    }

    public function create(): View
    {
        return view('admin.dishes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        $dish = Dish::create($data);
        return redirect()->route('admin.dishes.edit', $dish)->with('success','Dish created.');
    }

    public function show(Dish $dish): View
    {
        $dish->load('ingredients.supply');
        return view('admin.dishes.show', compact('dish'));
    }

    public function edit(Dish $dish): View
    {
        $dish->load('ingredients.supply');
        return view('admin.dishes.edit', compact('dish'));
    }

    public function update(Request $request, Dish $dish)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        $dish->update($data);
        return redirect()->route('admin.dishes.edit', $dish)->with('success','Dish updated.');
    }

    public function destroy(Dish $dish)
    {
        $dish->delete();
        return redirect()->route('admin.dishes.index')->with('success','Dish deleted.');
    }
}
