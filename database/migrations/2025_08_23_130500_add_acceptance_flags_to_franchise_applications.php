<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('franchise_applications', function (Blueprint $t) {
            if (!Schema::hasColumn('franchise_applications', 'accept_entry_fee')) {
                $t->boolean('accept_entry_fee')->default(false)->after('motivation');
            }
            if (!Schema::hasColumn('franchise_applications', 'accept_royalty')) {
                $t->boolean('accept_royalty')->default(false)->after('accept_entry_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('franchise_applications', function (Blueprint $t) {
            if (Schema::hasColumn('franchise_applications', 'accept_royalty')) {
                $t->dropColumn('accept_royalty');
            }
            if (Schema::hasColumn('franchise_applications', 'accept_entry_fee')) {
                $t->dropColumn('accept_entry_fee');
            }
        });
    }
};
