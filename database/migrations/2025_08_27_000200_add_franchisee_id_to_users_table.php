<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->ulid('franchisee_id')->nullable()->after('id');
            $t->foreign('franchisee_id')->references('id')->on('franchisees')->nullOnDelete();
            $t->index('franchisee_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropForeign(['franchisee_id']);
            $t->dropColumn('franchisee_id');
        });
    }
};
