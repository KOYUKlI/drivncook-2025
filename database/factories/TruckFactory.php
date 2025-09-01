<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TruckFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['Draft','Active','InMaintenance','Retired']);
        $acquired = $this->faker->optional()->dateTimeBetween('-2 years','-30 days','UTC');
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'code' => 'TRK-'.strtoupper(substr(\Illuminate\Support\Str::ulid()->toBase32(), -4)),
            'name' => 'Truck '.$this->faker->unique()->bothify('##'),
            'plate' => strtoupper($this->faker->bothify('??-###-??')),
            'vin' => strtoupper($this->faker->bothify('VF1###########')),
            'make' => $this->faker->randomElement(['Renault','Peugeot','Citroën','Mercedes']),
            'model' => $this->faker->randomElement(['Master','Boxer','Jumpy','Sprinter']),
            'year' => (int) $this->faker->numberBetween(date('Y')-8, date('Y')),
            'status' => $status,
            'acquired_at' => $acquired,
            'service_start' => $this->faker->optional()->dateTimeBetween($acquired ?? '-1 year','now','UTC'),
            'mileage_km' => $this->faker->numberBetween(20000, 250000),
            'franchisee_id' => null,
            'registration_doc_path' => 'private/docs/'.\Illuminate\Support\Str::random(8).'.pdf',
            'insurance_doc_path' => 'private/docs/'.\Illuminate\Support\Str::random(8).'.pdf',
        ];
    }
}
