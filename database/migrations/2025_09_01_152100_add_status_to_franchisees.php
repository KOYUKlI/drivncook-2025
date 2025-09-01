<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('franchisees')) {
            return;
        }
        Schema::table('franchisees', function (Blueprint $t) {
            if (!Schema::hasColumn('franchisees', 'status')) {
                $t->enum('status', ['Active','Draft','Retired'])->default('Active')->after('royalty_rate');
                $t->index('status');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('franchisees')) {
            return;
        }
        Schema::table('franchisees', function (Blueprint $t) {
            if (Schema::hasColumn('franchisees', 'status')) {
                $t->dropIndex(['status']);
                $t->dropColumn('status');
            }
        });
    }
};
