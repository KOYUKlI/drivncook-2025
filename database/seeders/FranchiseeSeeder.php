<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FranchiseeSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            ['email' => 'fr1@dc.test', 'name' => 'FR1 — Paris 11e', 'phone' => '+33 6 11 22 33 44', 'billing' => '11 Rue Oberkampf, 75011 Paris'],
            ['email' => 'fr2@dc.test', 'name' => 'FR2 — Nanterre', 'phone' => '+33 6 22 33 44 55', 'billing' => '5 Avenue Joliot-Curie, 92000 Nanterre'],
            ['email' => 'fr3@dc.test', 'name' => 'FR3 — Créteil', 'phone' => '+33 6 33 44 55 66', 'billing' => '2 Rue du Général Leclerc, 94000 Créteil'],
        ];

        foreach ($map as $row) {
            $user = User::where('email', $row['email'])->first();
            $fr = Franchisee::firstOrCreate(
                ['email' => $row['email']],
                [
                    'id' => (string) Str::ulid(),
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'billing_address' => $row['billing'],
                    'royalty_rate' => 0.0400,
                ]
            );

            if ($user && $user->franchisee_id !== $fr->id) {
                $user->forceFill(['franchisee_id' => $fr->id])->save();
            }
        }
    }
}
