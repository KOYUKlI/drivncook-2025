<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $aid = (string) Str::ulid();
        DB::table('franchise_applications')->insert([
            'id' => $aid, 'user_id' => null, 'full_name' => 'Alex Martin',
            'email' => 'alex.martin@example.com', 'phone' => '',
            'company_name' => 'AM Food', 'desired_area' => 'Paris Est',
            'entry_fee_ack' => true, 'royalty_ack' => true, 'central80_ack' => true,
            'status' => 'submitted', 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('franchise_application_events')->insert([
            'id' => (string) Str::ulid(), 'franchise_application_id' => $aid,
            'user_id' => null, 'from_status' => null, 'to_status' => 'submitted',
            'message' => 'Candidature soumise', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
