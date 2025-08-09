<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\DishIngredient;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DishIngredientController extends Controller
{
    public function store(Request $request, Dish $dish)
    {
        $data = $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'qty_per_dish' => 'required|numeric|min:0.0001',
            'unit' => 'nullable|string|max:50',
        ]);
        // Default unit from supply if not provided
        if (empty($data['unit'])) {
            $supply = Supply::find($data['supply_id']);
            $data['unit'] = $supply?->unit;
        }
        $data['dish_id'] = $dish->id;
        DishIngredient::create($data);
        return redirect()->route('admin.dishes.edit', $dish)->with('success','Ingredient added.');
    }

    public function destroy(Dish $dish, DishIngredient $ingredient)
    {
        if ($ingredient->dish_id !== $dish->id) {
            abort(404);
        }
        $ingredient->delete();
        return redirect()->route('admin.dishes.edit', $dish)->with('success','Ingredient removed.');
    }
}
