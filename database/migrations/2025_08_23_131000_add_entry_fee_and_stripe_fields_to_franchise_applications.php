<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('franchise_applications', function (Blueprint $t) {
            if (!Schema::hasColumn('franchise_applications', 'entry_fee_due')) {
                $t->decimal('entry_fee_due', 12, 2)->nullable()->after('motivation');
            }
            if (!Schema::hasColumn('franchise_applications', 'entry_fee_status')) {
                $t->enum('entry_fee_status', ['pending','paid','failed'])->default('pending')->after('entry_fee_due');
            }
            if (!Schema::hasColumn('franchise_applications', 'entry_fee_paid_at')) {
                $t->timestamp('entry_fee_paid_at')->nullable()->after('entry_fee_status');
            }
            if (!Schema::hasColumn('franchise_applications', 'stripe_session_id')) {
                $t->string('stripe_session_id')->nullable()->after('entry_fee_paid_at');
            }
            if (!Schema::hasColumn('franchise_applications', 'stripe_payment_intent')) {
                $t->string('stripe_payment_intent')->nullable()->after('stripe_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('franchise_applications', function (Blueprint $t) {
            foreach (['stripe_payment_intent','stripe_session_id','entry_fee_paid_at','entry_fee_status','entry_fee_due'] as $col) {
                if (Schema::hasColumn('franchise_applications', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
