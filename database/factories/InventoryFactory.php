<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Inventory> */
class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'supply_id' => Supply::factory(),
            'on_hand' => 0,
        ];
    }
}
